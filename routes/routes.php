<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;

$routes = new RouteCollection();

$routes->add('index', new Route('/',
    [
        '_controller' => 'UserController',
        'method'      => 'index',
    ],
    [], ['Auth'], '', [], 'GET'
));
$routes->add('create', new Route('/register',
    [
        '_controller' => 'UserController',
        'method'      => 'create',
    ],
    [], [], '', [], 'GET'
));
$routes->add('store', new Route('/register',
    [
        '_controller' => 'UserController',
        'method'      => 'store',
    ],
    [], [], '', [], 'POST'
));
//middleware auth mb
$routes->add('show', new Route('/users/{id}',
    [
        '_controller' => 'UserController',
        'method'      => 'show',
        'middleware'  => ['Auth'],
    ],
    ['id' => '[0-9]+'], ['Auth'], '', [], 'GET'
));

$routes->add('login', new Route('/login',
    [
        '_controller' => 'AuthController',
        'method'      => 'login',
    ],
    [], [], '', [], 'GET'
));

$routes->add('auth', new Route('/login',
    [
        '_controller' => 'AuthController',
        'method'      => 'auth',
    ],
    [], [], '', [], 'POST'
));

$routes->add('logout', new Route('/logout',
    [
        '_controller' => 'AuthController',
        'method'      => 'logout',
        'middleware'  => ['Auth'],
    ],
    [], ['Auth'], '', [], 'POST'
));

$routes->add('userAPI', new Route('/api/get_users/{type}/{key}',
    [
        '_controller' => 'Api\UserController',
        'method'      => 'getUsers',
        'middleware'  => ['Auth'],
    ],
    ['type' => '(json|xml)'], [], '', [], 'GET'
));

return $routes;