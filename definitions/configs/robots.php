<?php

use Psr\Container\ContainerInterface ;

use oihana\robots\enums\RobotsConfig      as Config      ;
use oihana\robots\enums\RobotsDefinition as Definitions ;

return
[
    Definitions::ROBOTS
        => fn( ContainerInterface $container ) :array
        => $container->get( Definitions::CONFIG )[ Config::ROBOTS ] ?? []
];
