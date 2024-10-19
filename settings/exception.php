<?php

use Monolog\Level;

return [
    'log' => true,
    'level_map' => [
        Level::Error->name => true,
        Level::Notice->name => true,
        Level::Alert->name => true,
        Level::Critical->name => true,
        Level::Debug->name => true,
        Level::Emergency->name => true,
        Level::Info->name => true,
    ]
];