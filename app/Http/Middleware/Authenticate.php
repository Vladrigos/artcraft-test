<?php

namespace App\Http\Middleware;

use App\Models\User;
use Symfony\Component\HttpFoundation\Request;

class Authenticate implements MiddlewareInterface
{
    protected $authorized = false;
    protected Request $request;
    protected $user = false;
    protected $auth = false;
    protected $hash;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        (!$this->setAuthStatus()) ?: $this->setUserData();
    }

    public function handle()
    {
        return $this->isAuth() ? 200 : new RedirectResponse('/');
    }

    protected function setAuthStatus()
    {
        if ($this->request->cookies->has('user_hash')) {
            $this->hash = $this->request->cookies->get('user_hash');
            $this->authorized = true;
            return true;
        }
        return false;
    }

    protected function setUserData(){
        $user = User::where('user_hash', '=', $this->hash)->get()->toArray();
        $this->user = $user;
    }

    public function isAuth()
    {
        return $this->authorized;
    }

    public function auth()
    {
        return $this->user;
    }
}
