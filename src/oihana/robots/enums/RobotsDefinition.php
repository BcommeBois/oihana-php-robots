<?php

namespace oihana\robots\enums;

use oihana\reflections\traits\ConstantsTrait;

/**
 * Enumeration of 'command:robots' command definitions keys.
 *
 * @package oihana\robots\enums
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class RobotsDefinition
{
    use ConstantsTrait ;

    const string APP_PATH    = 'appPath'    ;
    const string COMMAND     = 'command'    ;
    const string COMMANDS    = 'commands'   ;
    const string CONFIG      = 'config'     ;
    const string CONFIG_PATH = 'configPath' ;
    const string ROBOTS      = 'robots'     ;
}