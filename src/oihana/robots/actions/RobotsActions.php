<?php

namespace oihana\robots\actions;

/**
 * Aggregates all main actions related to `robots.txt` file management.
 *
 * This trait combines:
 *  - {@see RobotsCreateAction} for creating the `robots.txt` file.
 *  - {@see RobotsRemoveAction} for removing the `robots.txt` file.
 *
 * It can be used in console commands or services that need to provide both
 * creation and removal capabilities for the `robots.txt` file.
 *
 * @package oihana\robots\actions
 */
trait RobotsActions
{
    use RobotsCreateAction ,
        RobotsRemoveAction;
}