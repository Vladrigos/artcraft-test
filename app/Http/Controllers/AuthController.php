<?php

namespace App\Http\Controllers;

use App\Helpers\Csrf;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class AuthController extends Controller
{
    private UserService $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new UserService;
    }

    /*
     * GET /login
     */
    public function login()
    {
        $csrf = new Csrf();
        $csrf = $csrf->getToken();

        return $this->render('auth.login', ['csrf_token' => $csrf]);
    }

    /*
     * POST /logout
     */
    public function logout()
    {
        $request = Request::createFromGlobals();
        $hash = $request->cookies->get('user_hash');
        $response = new RedirectResponse('/');
        User::where('user_hash', $hash)->first()->update(['user_hash' => $hash]);
        $response->headers->setCookie(Cookie::create('user_hash', $hash, strtotime('-24 hour')));
        return $response;
    }

    /*
     * POST /login
     */
    public function auth()
    {
        $request = Request::createFromGlobals();
        $session = new Session();

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if ($user = User::where('password', md5($password))
            ->where('email', $email)
            ->first())
        {
            $hash = $this->service->generateCode();
            $user->user_hash = $hash;
            $user->save();
            $response = new RedirectResponse('/');
            $response->headers->setCookie(Cookie::create('user_hash', $hash, strtotime('+1 hour')));
            return $response;
        }

        return $this->redirect('/login');
    }
}