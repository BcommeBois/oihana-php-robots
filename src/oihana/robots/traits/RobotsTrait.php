<?php

namespace oihana\robots\traits;

use oihana\robots\options\RobotsOptions;

use oihana\commands\traits\FileTrait;
use oihana\files\exceptions\FileException;

/**
 * Provides logic to create and delete a `robots.txt` file for a project or website.
 *
 * This trait offers methods for:
 * - Creating a robots.txt file from options (path, contents, etc.)
 * - Deleting the robots.txt file with optional assertions
 * - Initializing internal options from raw input
 *
 * @package oihana\robots\traits
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
trait RobotsTrait
{
    use FileTrait ;

    /**
     * Key used in configuration arrays to refer to the robots.txt section.
     */
    public const string ROBOTS = 'robots' ;

    /**
     * Holds the `RobotsOptions` instance if initialized or injected.
     *
     * @var RobotsOptions|null
     */
    public ?RobotsOptions $robotsOptions ;

    /**
     * Creates the `robots.txt` file at the location and with the contents defined in the given options.
     *
     * If no options are passed, the method uses the internal `$robotsOptions` property, if available.
     *
     * Example:
     * ```php
     * $this->createRobots
     * (
     *     new RobotsOptions
     *     ([
     *         'file' => '/var/www/html/robots.txt',
     *         'contents' => 'User-agent: *' . PHP_EOL . 'Disallow: /private/'
     *     ])
     * );
     * ```
     *
     * @param null|array|RobotsOptions $options Optional options instance; if null, falls back to `$this->robotsOptions`.
     * @param bool                     $verbose The verbose mode.
     *
     * @return int `ExitCode::SUCCESS` (0) if creation succeeds.
     */
    public function createRobots( null|array|RobotsOptions $options = null , bool $verbose = false ) :int
    {
        $options = RobotsOptions::resolve( $this->robotsOptions , $options ) ;
        return $this->makeFile
        (
            filePath : $options->getFilePath() ,
            content  : $options->content       ,
            verbose  : $verbose
        ) ;
    }

    /**
     * Deletes the `robots.txt` file.
     *
     * If no options are passed, the method uses the internal `$robotsOptions` property, if available.
     *
     * @param null|array|RobotsOptions $options    Optional options instance; if null, falls back to `$this->robotsOptions`.
     * @param bool                     $verbose    The verbose mode.
     * @param bool                     $assertable If true, asserts file existence and permissions before deletion.
     *
     * @return int `ExitCode::SUCCESS` (0) if deletion succeeds.
     *
     * @throws FileException If the file cannot be deleted or assertions fail.
     */
    public function deleteRobots( null|array|RobotsOptions $options = null , bool $verbose = false , bool $assertable = false ):int
    {
        $options = RobotsOptions::resolve( $this->robotsOptions , $options ) ;
        return $this->deleteFile
        (
            filePath   : $options->getFilePath() ,
            verbose    : $verbose                ,
            assertable : $assertable             ,
        ) ;
    }

    /**
     * Initializes the `$robotsOptions` property from an input array.
     *
     * The array may contain either a full options array or a subkey `robots`.
     *
     * Example:
     * ```php
     * $this->initializeRobotsOptions
     * ([
     *     'file'     => '/var/www/html/robots.txt',
     *     'contents' => 'User-agent: *' . PHP_EOL . 'Disallow: /tmp/'
     * ]);
     * ```
     *
     * @param array<string, mixed> $init Array of options, or an array containing the `'robots'` key.
     *
     * @return static For method chaining.
     */
    protected function initializeRobotsOptions( array $init = [] ) :static
    {
        $this->robotsOptions = new RobotsOptions( $init[ self::ROBOTS ] ?? $init ) ;
        return $this ;
    }
}