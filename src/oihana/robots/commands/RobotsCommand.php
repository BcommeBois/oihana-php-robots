<?php

namespace oihana\robots\commands;

use oihana\robots\options\RobotsOption;
use Throwable;
use UnexpectedValueException;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

use oihana\enums\Char;
use oihana\commands\enums\CommandArg;
use oihana\commands\enums\ExitCode;
use oihana\commands\exceptions\ExitException;
use oihana\commands\Kernel;
use oihana\commands\options\CommandOption;
use oihana\robots\actions\RobotsActions;
use oihana\robots\enums\RobotsAction;

use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function oihana\commands\helpers\clearConsole;

/**
 * Console command to manage a project's robots.txt file.
 *
 * Overview
 * - Provides sub-commands to create and remove the robots.txt file.
 * - Delegates the actual work to methods brought in by the {@see RobotsActions} trait,
 *   which aggregates {@see \oihana\robots\actions\RobotsCreateAction} and {@see \oihana\robots\actions\RobotsRemoveAction}.
 * - Validates the requested action against {@see RobotsAction}.
 *
 * Usage
 *   bin/console command:robots <action> [options]
 *
 * Arguments
 * - action (required): One of the supported actions declared in {@see RobotsAction}.
 *   - "create": Create the robots.txt file (if a path is provided with --file, it will be used; otherwise default configuration applies).
 *   - "remove": Delete the robots.txt file.
 *
 * Options
 * -c, --clear            Clear the console before running the command (from {@see CommandOption}).
 * -f, --file=PATH        The robots.txt file path (from {@see RobotsOption}).
 *
 * Behavior and resolution rules
 * - When --file is a relative path, it is resolved against the current working directory.
 * - For creation and deletion, directory existence and permissions are validated. A failure raises a FileException in the action layer.
 * - If the action name does not match {@see RobotsAction} or the corresponding method is missing, an UnexpectedValueException is thrown.
 * - The command logs to the console and returns an appropriate {@see ExitCode}.
 *
 * Exit codes
 * - 0 ({@see ExitCode::SUCCESS}): Command executed successfully.
 * - 1 ({@see ExitCode::FAILURE}): An error occurred (invalid action, IO failure, etc.).
 *
 * Exceptions
 * - {@see \oihana\files\exceptions\FileException}: When the target path is invalid or not writable (raised by actions).
 * - {@see UnexpectedValueException}: When an invalid or unsupported action is provided.
 * - {@see ExitException}: Used for controlled early exits returning SUCCESS.
 *
 * Configuration and DI
 * - The command can receive initial options via its constructor ($init). Robot-specific options are initialized
 *   via {@see \oihana\robots\traits\RobotsTrait::initializeRobotsOptions()} and stored in $this->robotsOptions.
 * - Global command options are configured with {@see CommandOption::configure()} and robot-specific ones with {@see RobotsOption::configure()}.
 *
 * Examples
 * - Create a new robots.txt file
 * ```php
 * // bin/console command:robots create
 * ```
 *
 * - Create a custom robots.txt file
 * ```php
 * // bin/console command:robots create --file /var/www/my-website/htdocs/robots.txt
 * ```
 *
 * - Remove a robots.txt file
 * ```php
 * // bin/console command:robots remove
 * ```
 *
 * - Remove a custom robots.txt file
 * ```php
 * // bin/console command:robots remove --file /var/www/my-website/htdocs/robots.txt
 * ```
 *
 * - Create a new robots.txt file after clearing the console
 * ```php
 * // bin/console command:robots create --clear
 * ```
 *
 * @package oihana\robots\commands
 */
class RobotsCommand extends Kernel
{
    /**
     * Initializes the command and its robots options. The $init array can be either a flat options array
     * or contain a 'robots' sub-array. In both cases, options are forwarded to RobotsOptions via
     * initializeRobotsOptions().
     *
     * Example (programmatic registration with initial robots options):
     * ```php
     * use DI\Container;
     * use oihana\robots\commands\RobotsCommand;
     *
     * $container = new Container();
     * $command = new RobotsCommand(
     *     null, // let the parent/kernel resolve the name or use the default
     *     $container,
     *     [
     *         'robots' => [
     *             'file'    => '/var/www/my-website/htdocs/robots.txt',
     *             'content' => "User-agent: *\nDisallow: /private/"
     *         ]
     *     ]
     * );
     * ```
     *
     * @param string|null  $name       The command name (optional; defaults to null).
     * @param Container|null $container A PSR-11 compatible container instance (optional).
     * @param array         $init       Initial options, may include a 'robots' key.
     *
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct
    (
        ?string    $name = null ,
        ?Container $container = null ,
        array      $init = []
    )
    {
        parent::__construct( $name , $container , $init );
        $this->initializeRobotsOptions( $init ) ;
    }

    use RobotsActions ;

    /**
     * The default name of the command.
     */
    public const string NAME = 'command:robots' ;

    /**
     * Configures the current command: arguments and options.
     *
     * This method registers:
     * - A required "action" argument ({@see CommandArg::ACTION}) that must be one of {@see RobotsAction} values
     *   (e.g., "create", "remove").
     * - Global options via {@see CommandOption::configure()} (notably -c|--clear to clear the console).
     * - Robot-specific options via {@see RobotsOption::configure()} (notably -f|--file to target a robots.txt path).
     *
     * Notes
     * - To customize what options are exposed, you can override this method in a subclass and delegate with flags to
     *   the static configurators. For example, you can disable the clear option or the file option by passing "false"
     *   to their respective helper methods.
     *
     * Examples
     * - Default configuration (this class)
     * ```php
     * // Registers the required "action" argument and both the global and robots options.
     * $this->addArgument(CommandArg::ACTION, InputArgument::REQUIRED, 'Action to perform a `robot` subcommand: create, remove, etc.');
     * CommandOption::configure($this); // adds -c | --clear
     * RobotsOption::configure($this);  // adds -f | --file
     * ```
     *
     * - Override in a subclass to disable the clear option
     * ```php
     * use Symfony\Component\Console\Input\InputArgument;
     * use oihana\commands\options\CommandOption;
     * use oihana\robots\options\RobotsOption;
     * use oihana\commands\enums\CommandArg;
     *
     * protected function configure(): void
     * {
     *     $this->addArgument(CommandArg::ACTION, InputArgument::REQUIRED, 'Action to perform a `robot` subcommand.');
     *     CommandOption::configure($this, false); // do not add -c|--clear
     *     RobotsOption::configure($this);         // keep -f|--file
     * }
     * ```
     *
     * - Override in a subclass to disable the file option
     * ```php
     * use Symfony\Component\Console\Input\InputArgument;
     * use oihana\commands\options\CommandOption;
     * use oihana\robots\options\RobotsOption;
     * use oihana\commands\enums\CommandArg;
     *
     * protected function configure(): void
     * {
     *     $this->addArgument(CommandArg::ACTION, InputArgument::REQUIRED, 'Action to perform a `robot` subcommand.');
     *     CommandOption::configure($this);          // keep -c|--clear
     *     RobotsOption::configure($this, false);    // do not add -f|--file
     * }
     * ```
     *
     * @return void
     */
    protected function configure() : void
    {
        $this->addArgument( CommandArg::ACTION , InputArgument::REQUIRED , 'Action to perform a `robot` subcommand: create, remove, etc.' ) ;
        CommandOption::configure( $this ) ;
        RobotsOption::configure( $this ) ;
    }

    /**
     * Executes the current command.
     * @return int 0 if everything went fine, or an exit code
     * @throws LogicException When this abstract method is not implemented
     * @see setCode()
     */
    protected function execute( InputInterface $input , OutputInterface $output ) : int
    {
        [ $io , $timestamp ] = $this->startCommand( $input , $output );

        $this->initializeConsoleLogger( $output ) ;

        clearConsole( $input->getOption( CommandOption::CLEAR ) ?? $this->commandOptions?->clearable ?? false );

        try
        {
            $this->action = $input->getArgument(CommandArg::ACTION ) ?? Char::EMPTY ;

            if( is_array( $this->actions ) && count( $this->actions ) > 0 )
            {
                if( !in_array( $this->action , $this->actions , true ) )
                {
                    throw new UnexpectedValueException
                    (
                        sprintf
                        (
                            'The action "%s" is not allowed. Allowed: %s' ,
                            $this->action ,
                            json_encode( $this->actions , JSON_UNESCAPED_SLASHES )
                        )
                    );
                }
            }

            if( RobotsAction::includes( $this->action ) )
            {
                if( method_exists( $this , $this->action )  )
                {
                    $status = $this->{ $this->action }( $input , $output ) ;
                }
                else
                {
                    throw new UnexpectedValueException( sprintf( 'No method matches the action "%s"' , $this->action ) ) ;
                }
            }
            else
            {
                throw new UnexpectedValueException( sprintf( 'The action "%s" is not valid.' , $this->action ) ) ;
            }
        }
        catch( ExitException )
        {
            $status = ExitCode::SUCCESS ;
        }
        catch ( Throwable $exception )
        {
            $io->error( sprintf( 'The command failed :  %s' , $exception->getMessage() ) ) ;
            $status = ExitCode::FAILURE ;
        }

        $io->newLine();

        return $this->endCommand( $input , $output , $status , $timestamp );
    }
}