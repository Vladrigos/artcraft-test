<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

$routes->add('route2', new Route('/default/{default}',
    array(
        '_controller' => 'app\Http\Controllers\DefaultController::default',
    ),
    array(),
    array(
        'utf8' => true,
    )
));

return $routes;