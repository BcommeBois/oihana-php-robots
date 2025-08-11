<?php

namespace oihana\commands\plugins\robots\options;

use oihana\options\Option;

/**
 * The enumeration of the robot.txt command options.
 */
class RobotsOption extends Option
{
    public const string CONTENT     = 'content'     ;
    public const string FILE        = 'file'        ;
    public const string GROUP       = 'group'       ;
    public const string OVERWRITE   = 'owner'       ;
    public const string OWNER       = 'owner'       ;
    public const string PERMISSIONS = 'permissions' ;
}