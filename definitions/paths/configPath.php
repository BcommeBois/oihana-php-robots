<?php

use oihana\robots\enums\RobotsDefinition as Definitions;

return
[
    Definitions::CONFIG_PATH => fn() :string => __CONFIG__
];
