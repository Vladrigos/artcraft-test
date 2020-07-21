<?php

namespace App\Http;

use eftec\bladeone\BladeOne;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Router;
use App\Components\Database\DB;
use App\Components\Router\Router as MyRouter;
use Illuminate\Database\Capsule\Manager as Capsule;

class Kernel
{
    /**
     * The router instance
     */
    protected MyRouter $router;
    /**
     * The application's middleware stack.
     */
    protected array $middleware = [];
    /**
     * Database.
     */
    protected DB $db;

    protected BladeOne $blade;

    protected Request $request;

    protected array $allMiddlewares = [
        'Auth'            => '\App\Http\Middleware\Auth',
        'VerifyCsrfToken' => '\App\Http\Middleware\VerifyCsrfToken',
    ];

    protected array $requiredMiddlewares = [
        'VerifyCsrfToken'
    ];

    protected array $routeMiddlewares = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->init();
    }

    public function handle(): Response
    {
        try
        {
            $attributes = $this->router->getAttributes();
            $controller = $attributes['_controller'];

            $result = $this->setMiddlewares($attributes);
            if ($result instanceof Response)
            {
                return $result;
            }
            $response = $this->callController($controller, $attributes);
        }
        catch (ResourceNotFoundException $e)
        {
            $html = $this->blade->run('404');
            $response = new Response($html, Response::HTTP_NOT_FOUND);
        }
        return $response;
    }

    protected function init(): void
    {
        $dbconfig = require 'config/database.php';
        $capsule = new Capsule;
        $this->router = new MyRouter($this->request);
        $this->db = new DB($capsule, $dbconfig);
        $views = dirname(__DIR__, 2) . '/resources/views';
        $cache = dirname(__DIR__, 2) . '/storage/cache';
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    }

    public function callController($controller, array $attributes): Response
    {
        $controllerPath = "\App\Http\Controllers\\" . $controller;
        $controllerActionName = $attributes['method'];
        unset($attributes['method']);
        unset($attributes['_route']);
        unset($attributes['_controller']);
        $controllerObject = new $controllerPath(); //(rofl)

        return call_user_func_array([$controllerObject, $controllerActionName], $attributes);
    }

    protected function setMiddlewares(&$attributes)
    {
        if (isset($attributes['middleware']))
        {
            $middlewares = $attributes['middleware'];
            unset($attributes['middleware']);
            $this->routeMiddlewares = $middlewares;
        }
        return $this->callMiddlewares();
    }

    protected function callMiddlewares()
    {
        $middlewares = array_unique(array_merge($this->requiredMiddlewares, $this->routeMiddlewares));
        foreach ($middlewares as $middleware)
        {
            $middlewareObj = new $this->allMiddlewares[$middleware];
            $middlewareResult = $middlewareObj->handle();//

            if ($middlewareResult instanceof Response)
            {
                return $middlewareResult;
            } elseif ((is_int($middlewareResult) && ($middlewareResult !== 200)))
            {
                $page = $this->blade->run('error', ['status_code' => $middlewareResult]);
                return new Response($page, $middlewareResult);
            }
        }
        return false;
    }
}