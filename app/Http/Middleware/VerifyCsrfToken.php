<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Helpers\Csrf;

class VerifyCsrfToken implements MiddlewareInterface
{
    public function handle()
    {
        $csrf = new Csrf();
        return $csrf->checkTokensEqual() ? 200 : 403;
    }
}