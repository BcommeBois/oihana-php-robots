<?php

use DI\Container;

use oihana\enums\IniOptions;
use oihana\robots\enums\RobotsConfig     as Config      ;
use oihana\robots\enums\RobotsDefinition as Definitions ;

use function oihana\init\initConfig ;
use function oihana\init\initDefaultTimezone;
use function oihana\init\initErrors;
use function oihana\init\initMemoryLimit;

return
[
    Definitions::CONFIG
        => fn( Container $container )
        => initConfig
        (
            basePath : $container->get( Definitions::CONFIG_PATH )  ,
            init     : function( array $config ) use ( $container ) :array
            {
                initDefaultTimezone (  $config[ Config::TIMEZONE         ] ?? null ) ;
                initErrors          (        $config[ Config::ERRORS           ] ?? null , $container->get( Definitions::APP_PATH ) ) ;
                initMemoryLimit     ( $config[ IniOptions::MEMORY_LIMIT ] ?? null ) ;
                return $config ;
            }
        )
];
