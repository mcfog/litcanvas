<?php

const CANVAS_WIDTH = 1000;
const CANVAS_HEIGHT = 1000;

return array_merge_recursive([
    'container' => [
        'Twig_Environment::' => [
            'options' => [
                'value',
                [
                    'cache' => __DIR__ . '/data/cache/twig',
                    'auto_reload' => true,
                ]
            ],
        ],
        'Stash\Driver\FileSystem::' => [
            'options' => [
                'value',
                [
                    'path' => __DIR__ . '/data/cache/stash',
                ]
            ],
        ],
        'Predis\Client::' => [
            0 => [
                'value',
                [
                    'scheme' => 'tcp',
                    'host' => '127.0.0.1',
                    'port' => 6379,
                    'database' => 1
                ]
            ]
        ],
    ],
    'log' => [
        [
            '\Monolog\Handler\RotatingFileHandler',
            [
                __DIR__ . '/data/log/log'
            ]
        ],
    ],
], json_decode($_SERVER['LITCANVAS_CONFIG'], true));
