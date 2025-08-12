<?php

namespace oihana\robots\actions;

use oihana\commands\enums\ExitCode;
use oihana\commands\traits\UITrait;

use oihana\files\exceptions\DirectoryException;
use oihana\files\exceptions\FileException;
use oihana\robots\options\RobotsOption;
use oihana\robots\traits\RobotsTrait;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function oihana\files\path\isAbsolutePath;

/**
 * Provides the action to create a new `robots.txt` file.
 *
 * This trait can be used in console commands to generate a `robots.txt` file,
 * optionally specifying a custom file path through the `--file` or `-f` option.
 *
 * It uses the {@see RobotsTrait} for robot-specific logic and the {@see UITrait} for
 * Symfony Console input/output helpers.
 *
 * @package oihana\robots\actions
 */
trait RobotsCreateAction
{
    use RobotsTrait ,
        UITrait     ;

    /**
     * Creates a new robots.txt file.
     *
     * If the `--file` (or `-f`) option is provided, it attempts to use that as the target path.
     * The path can be absolute or relative. For relative paths, it resolves against the current working directory,
     * and verifies that the parent directory exists and is writable.
     *
     * On failure to write to the specified directory, a {@see FileException} is thrown.
     *
     * During execution, this method uses a styled Symfony Console IO interface to display progress and
     * success messages.
     *
     * @param InputInterface  $input  The input interface from the console command.
     * @param OutputInterface $output The output interface from the console command.
     *
     * @return int Returns {@see ExitCode::SUCCESS} on successful file creation.
     *
     * @throws DirectoryException If the path cannot be created due to directory permission or path issues.
     */
    public function create( InputInterface $input, OutputInterface $output ) :int
    {
        $io = $this->getIO( $input , $output ) ;

        $options = [] ;

        $path = $input->getOption( RobotsOption::PATH  ) ;
        if( $path )
        {
            if( isAbsolutePath( $path ) )
            {
                $options[ RobotsOption::PATH ] = $path ;
            }
            else
            {
                $relativePath = getcwd() . DIRECTORY_SEPARATOR . $path ;
                if ( is_dir( $relativePath ) && is_writable( $relativePath ) )
                {
                    $options[ RobotsOption::PATH ] = $relativePath ;
                }
                else
                {
                    throw new DirectoryException( sprintf( 'Failed to create the "robots.txt" file in the directory %s ', $path ) ) ;
                }
            }
        }

        $content = $input->getOption( RobotsOption::CONTENT ) ;
        if( $content )
        {
            $options[ RobotsOption::CONTENT ] = str_replace('\n', PHP_EOL, $content);
        }

        $this->runIOAction
        (
            callback :  fn( ?SymfonyStyle $io ) => $this->createRobots( $options ) ,
            io       : $io ,
            title    : 'Creates the robots.txt file' ,
            finish   : '🤖️  The robots.txt file is ready.'
        ) ;

        return ExitCode::SUCCESS ;
    }
}