<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Session\Session;

class Csrf
{
    protected Request $request;
    protected Session $session;
    protected string $sessionToken;
    protected $requestToken;

    public function __construct()
    {
        $this->session = new Session;
        $this->request = Request::createFromGlobals();
        $this->checkToken();
    }

    public function setToken(): void
    {
        $this->sessionToken = $this->generateToken();
        $this->session->set('csrf_token', $this->sessionToken);
    }

    public function getToken(): string
    {
        return $this->sessionToken;
    }

    public function getRequestToken() : string
    {
        return $this->requestToken;
    }

    public function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    public function checkToken() : bool
    {
        if ($this->session->has('csrf_token'))
        {
            $this->sessionToken = $this->session->get('csrf_token');
        } else
        {
            $this->setToken();
        }
        if ($this->request->request->has('csrf_token'))
        {
            $this->requestToken = $this->request->request->get('csrf_token');
        }
        return false;
    }

    public function checkTokensEqual()
    {
        if ($this->request->isMethod('POST'))
        {
            if ($this->requestToken != NULL)
            {
                return hash_equals($this->sessionToken, $this->requestToken) ? true : false;
            }
        }
        return true;
    }
}