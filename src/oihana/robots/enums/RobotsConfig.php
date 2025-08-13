<?php

namespace oihana\robots\enums;

use oihana\reflect\traits\ConstantsTrait;

/**
 * Enumeration of 'command:robots' command config keys.
 *
 * @package oihana\robots\enums
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class RobotsConfig
{
    use ConstantsTrait ;

    public const string COMMAND   = 'command'   ;
    public const string ERRORS    = 'errors'    ;
    public const string ROBOTS    = 'robots'    ;
    public const string TIMEZONE  = 'timezone'  ;
}