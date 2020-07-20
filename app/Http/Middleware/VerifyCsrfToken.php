<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class VerifyCsrfToken implements MiddlewareInterface
{
    protected Request $request;
    protected Session $session;
    protected $requestToken;
    protected $sessionToken;

    public function __construct()
    {
        $this->session = new Session;
        $this->request = Request::createFromGlobals();
        $this->checkToken();
    }

    public function handle()
    {
        return $this->checkTokensEqual() ? 200 : 403;
    }

    public function setToken()
    {
        $this->sessionToken = $this->generateToken();
        $this->session->set('csrf_token', $this->sessionToken);
    }

    public function getToken()
    {
        return $this->sessionToken;
    }

    public function getRequestToken(){
        return $this->requestToken;
    }

    public function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function checkToken()
    {
        if ($this->session->has('csrf_token')) {
            $this->sessionToken = $this->session->get('csrf_token');
        }else{
            $this->setToken();
        }
        if($this->request->request->has('csrf_token')){
            $this->requestToken = $this->request->request->get('csrf_token');
        }
        return false;
    }

    protected function checkTokensEqual()
    {
        if($this->request->isMethod('POST')){
            return hash_equals($this->sessionToken, $this->requestToken) ? true : false;
        }
        return true;
    }
}