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
 * Command to manage `robots.txt` file operations such as create or remove.
 *
 * This command delegates the action execution to methods defined
 * in the {@see RobotsActions} trait.
 *
 * It supports subcommands like `create` and `remove` to manipulate the robots.txt file.
 *
 * @package oihana\robots\commands
 *
 * @example Create a new robots.txt file
 * ```shell
 * bin/console command:robots create
 * ```
 *
 * @example Create a custom robots.txt file
 * ```shell
 * bin/console command:robots create --file /var/www/my-website/htdocs/robots.txt
 * ```
 *
 * @example Remove a robots.txt file
 * ```shell
 * bin/console command:robots remove
 * ```
 *
 * @example Remove a custom robots.txt file
 * ```shell
 * bin/console command:robots remove --file /var/www/my-website/htdocs/robots.txt
 * ```
 *
 * @example Create a new robots.txt file after clearing the console
 * ```shell
 * bin/console command:robots create --clear
 * ```
 */
class RobotsCommand extends Kernel
{
    /**
     * Creates a new MemcachedCommand.
     * @param string|null $name
     * @param Container|null $container
     * @param array $init
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct
    (
        ?string    $name ,
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
     * Configures the current command.
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