<?php

namespace oihana\robots\actions;

use oihana\commands\enums\ExitCode;
use oihana\commands\traits\UITrait;

use oihana\files\exceptions\FileException;
use oihana\robots\options\RobotsOption;
use oihana\robots\traits\RobotsTrait;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function oihana\files\path\isAbsolutePath;

/**
 * Provides the action to remove an existing `robots.txt` file.
 *
 * This trait can be used in console commands to delete a `robots.txt` file,
 * optionally specifying a custom file path through the `--file` or `-f` option.
 *
 * It uses the {@see RobotsTrait} for robot-specific logic and the {@see UITrait} for
 * Symfony Console input/output helpers.
 *
 * @package oihana\robots\actions
 */
trait RobotsRemoveAction
{
    use RobotsTrait ,
        UITrait     ;

    /**
     * Removes an existing robots.txt file.
     *
     * If the `--file` (or `-f`) option is provided, it attempts to delete the file at the specified path.
     * The path can be absolute or relative. For relative paths, it resolves against the current working directory,
     * and verifies that the parent directory exists and is writable.
     *
     * If the file cannot be removed due to directory permission or path issues, a {@see FileException} is thrown.
     *
     * The method provides console feedback using a styled Symfony Console IO interface,
     * showing progress and success messages.
     *
     * @param InputInterface  $input  The input interface from the console command.
     * @param OutputInterface $output The output interface from the console command.
     *
     * @return int Returns {@see ExitCode::SUCCESS} on successful file removal.
     *
     * @throws FileException If the file cannot be removed due to directory permission or path issues.
     */
    public function remove( InputInterface $input, OutputInterface $output ) :int
    {
        $io = $this->getIO( $input , $output ) ;

        $options = [] ;

        $file = $input->getOption( RobotsOption::FILE  ) ;
        if( $file )
        {
            if( isAbsolutePath( $file ) )
            {
                $options[ RobotsOption::FILE ] = $file ;
            }
            else
            {
                $relativePath = getcwd() . DIRECTORY_SEPARATOR . $file ;
                $parentDir    = dirname( $relativePath ) ;
                if ( is_dir( $parentDir ) && is_writable( $parentDir ) )
                {
                    $options[ RobotsOption::FILE ] = $relativePath ;
                }
                else
                {
                    throw new FileException( sprintf( 'Failed to remove the file %s ', $file ) ) ;
                }
            }
        }

        $this->runIOAction
        (
            callback :  fn( ?SymfonyStyle $io ) => $this->deleteRobots( $options ) ,
            io       : $io ,
            title    : 'Remove the robots.txt file' ,
            finish   : '🤖️  The robots.txt file is removed.'
        ) ;

        return ExitCode::SUCCESS ;
    }
}