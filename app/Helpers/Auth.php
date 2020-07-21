<?php

namespace App\Helpers;

use App\Models\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class Auth
{
    protected bool $authorized = false;
    protected Request $request;
    protected $user;
    protected string $hash;

    public function __construct()
    {
        $this->request = Request::createFromGlobals();
        (!$this->setAuthStatus()) ?: $this->setUserData();
    }

    public function isAuth()
    {
        return $this->authorized;
    }

    public function getUser()
    {
        return $this->user;
    }

    protected function setAuthStatus()
    {
        if ($this->request->cookies->has('user_hash'))
        {
            $this->hash = $this->request->cookies->get('user_hash');
            $this->authorized = true;
            return true;
        }
        return false;
    }

    protected function setUserData()
    {
        $user = User::where('user_hash', '=', $this->hash)->first();
        $this->user = $user;
    }
}