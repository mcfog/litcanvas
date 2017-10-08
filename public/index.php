<?php

use Litcanvas\Bolt\LCApp;
use Litcanvas\Bolt\LCContainer;

require __DIR__ . '/../vendor/autoload.php';

LCApp::run(new LCContainer([
    'config' => ['value', require __DIR__ . '/../config.php'],
]));
