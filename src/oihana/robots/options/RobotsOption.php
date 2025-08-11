<?php

namespace oihana\robots\options;

use oihana\options\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * The enumeration of the robot.txt command options.
 *
 * @package oihana\robots\options
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class RobotsOption extends Option
{
    public const string CONTENT     = 'content'     ;
    public const string FILE        = 'file'        ;
    public const string GROUP       = 'group'       ;
    public const string OVERWRITE   = 'overwrite'   ;
    public const string OWNER       = 'owner'       ;
    public const string PERMISSIONS = 'permissions' ;

    /**
     * Configures the options of the current command.
     *
     * @param Command $command The command reference to configure.
     * @param bool    $hasFile Indicates if the file option is configured.
     *
     * @return Command
     */
    public static function configure
    (
        Command $command ,
        bool    $hasFile = true ,
    )
    :Command
    {
        if( $hasFile )
        {
            $command->addOption
            (
                name        : self::FILE ,
                shortcut    :  'f' ,
                mode        : InputOption::VALUE_OPTIONAL ,
                description :  'The robots.txt file path.'
            ) ;
        }

        return $command ;
    }
}