<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Comments',
    ['path' => '/comments'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);

Router::prefix('admin', function ($routes) {
    $routes->plugin(
        'Comments',
        ['path' => '/comments'],
        function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
    });
});
