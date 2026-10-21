<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/config',
        __DIR__.'/database',
        __DIR__.'/lang',
        __DIR__.'/public',
        __DIR__.'/routes',
        __DIR__.'/tests',
    ])
    ->withPhpSets(php85: true)
    ->withSets([
        LaravelSetList::LARAVEL_100,
        LaravelSetList::LARAVEL_110,
        LaravelSetList::LARAVEL_120,
        LaravelSetList::LARAVEL_130_WITHOUT_ATTRIBUTES,
    ]);
