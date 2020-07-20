<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;

$routes = new RouteCollection();

$routes->add('index', new Route('/',
    [
        '_controller' => 'UserController',
        'method'     => 'index',
    ],
    [],['Auth'],'',[],'GET'
));
$routes->add('create', new Route('/register',
    [
        '_controller' => 'UserController',
        'method'     => 'create',
    ],
    [],[],'',[],'GET'
));
$routes->add('store', new Route('/register',
    [
        '_controller' => 'UserController',
        'method'     => 'store',
    ],
    [],[],'',[],'POST'
));
//middleware auth mb
$routes->add('show', new Route('/{id}',
    [
        '_controller' => 'UserController',
        'method'     => 'show',
    ],
    ['id' => '[0-9]+'],['Auth'],'',[],'GET'
));

$routes->add('login', new Route('/login',
    [
        '_controller' => 'UserController',
        'method'     => 'login',
    ],
    [],[],'',[],'GET'
));

$routes->add('loginPost', new Route('/login',
    [
        '_controller' => 'UserController',
        'method'     => 'loginPost',
    ],
    [],[],'',[],'POST'
));

$routes->add('userAPI', new Route('/api/get_users/{type}/{key}',
    [
        '_controller' => 'Api\UserController',
        'method'     => 'getUsers',
    ],
    ['type' => '(json|xml)'],[],'',[],'GET'
));

return $routes;