<?php

use App\Http\Controllers\GifController;
use App\Routing\RouteDefinition;
use FastRoute\RouteCollector;

/** @var RouteCollector $routeCollector */

$routeCollector->addGroup('/v1', function (RouteCollector $routeCollector) {
    $routeCollector->get(
        '/gifs/search',
        new RouteDefinition(
            GifController::class,
            'search',
            true
        )
    );

    $routeCollector->get(
        '/gifs/random',
        new RouteDefinition(
            GifController::class,
            'fetchRandom',
            true
        )
    );
});
