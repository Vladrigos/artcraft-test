<?php

namespace App\Http;

use Symfony\Component\Routing\Router;

class Kernel 
{
    /**
     * The router instance
     */
    protected $router;
    /**
     * The application's middleware stack.
     */
    protected $middleware = [];

    public function __construct(Router $router)
    {

    }
}