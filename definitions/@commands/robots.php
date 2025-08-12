<?php

use DI\Container;

use oihana\enums\Param;

use oihana\robots\enums\RobotsAction;
use oihana\robots\enums\RobotsDefinition as Definitions;
use oihana\robots\commands\RobotsCommand;

// bin/console command:robots create
// bin/console command:robots remove

return
[
    RobotsCommand::NAME => fn( Container $container ) => new RobotsCommand
    (
        RobotsCommand::NAME ,
        $container ,
        [
            // ------------------ Default

            Param::DESCRIPTION => 'Manage the robots.txt.' ,
            Param::HELP        => 'This command allows manage the memcached tool.' ,

            // ------------------ Actions

            // Param::ACTIONS =>
            // [
            //     RobotsAction::CREATE
            // ] ,

            // ------------------ Config

            ...$container->get( Definitions::COMMAND ) , // config[ 'command' ] -> commandOptions
            ...$container->get( Definitions::ROBOTS  ) , // config[ 'robots'  ] -> robotOptions
        ]
    )
];
