<?php

use oihana\robots\enums\RobotsDefinitions as Definitions;

return
[
    Definitions::CONFIG_PATH => fn() :string => __CONFIG__
];
