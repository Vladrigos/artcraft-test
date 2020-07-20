<?php

namespace App\Components\Router;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Router implements RouterInterface
{
    private RouteCollection $routes;
    private UrlMatcher $matcher;
    private RequestContext $context;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->init();
    }

    public function getAttributes() : array
    {
        $path = $this->request->getPathInfo();
        return $this->matcher->match($path);
    }

    private function init() : void
    {
        $this->setRoutes();
        $this->context = new RequestContext();
        $this->context->fromRequest($this->request);
        $this->matcher = new UrlMatcher($this->routes, $this->context);
    }

    private function setRoutes() : void
    {
        $this->routes = require "routes/routes.php";
    }

}



