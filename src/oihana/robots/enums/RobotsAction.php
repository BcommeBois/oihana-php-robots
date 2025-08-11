<?php

namespace oihana\robots\enums;

use oihana\reflections\traits\ConstantsTrait;

/**
 * The enumeration of actions in the RobotsCommand.
 *
 * @package oihana\robots\enums
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
class RobotsAction
{
    use ConstantsTrait ;

    /**
     * Creates a new robots.txt file.
     */
    public const string CREATE = 'create' ;

    /**
     * The remove action.
     */
    public const string REMOVE = 'remove' ;
}