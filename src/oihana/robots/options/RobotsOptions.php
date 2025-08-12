<?php

namespace oihana\robots\options;

use oihana\options\Options;
use oihana\enums\Char;
use function oihana\files\path\joinPaths;

/**
 * The options of the robot.txt command.
 * @package oihana\robots\options
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class RobotsOptions extends Options
{
    /**
     * The 'robots.txt' file name.
     */
    public const string FILE = 'robots.txt';

    /**
     * If true, appends content instead of overwriting. Default: false.
     */
    public bool $append = false ;

    /**
     * The content of the robots.txt file.
     * @var string|null
     */
    public ?string $content = null ;

    /**
     * If true, creates parent directories if they do not exist. Default: true.
     */
    public bool $force = true ;

    /**
     * Set the group of the file.
     * @var string|null
     */
    public ?string $group = null ;

    /**
     * If true, creates parent directories if they do not exist. Default: true.
     */
    public bool $lock = true ;

    /**
     * If true, overwrites existing files. Default: true.
     * @var bool|null
     */
    public ?bool $overwrite = true ;

    /**
     * Set the owner of the file.
     * @var string|null
     */
    public ?string $owner = null ;

    /**
     * Set the 'robots.txt' file path.
     * @var string|null
     */
    public ?string $path = null ;

    /**
     * File permissions to set (octal). Default: 0644.
     * @var int|null
     */
    public ?int $permissions = null ;

    /**
     * Returns the full 'robots.txt' file path.
     * @return string
     */
    public function getFilePath():string
    {
        return joinPaths( $this->path ?? Char::EMPTY , self::FILE ) ;
    }

    /**
     * Returns the string expression of the object.
     * @return string
     */
    public function __toString() : string
    {
        return $this->getFilePath() ;
    }
}