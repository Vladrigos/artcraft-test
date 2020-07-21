<?php

namespace App\Http\Middleware;

use App\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Helpers\Auth as AuthHelper;

class Auth implements MiddlewareInterface
{
    public function handle()
    {
        $auth = new AuthHelper();
        return $auth->isAuth() ? 200 : new RedirectResponse('/login');
    }
}
