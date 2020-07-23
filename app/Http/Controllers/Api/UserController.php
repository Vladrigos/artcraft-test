<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    public function getUsers(string $type, string $token)
    {
        $user = User::where('api_token', $token)->first();

        if((strtotime($user->token_end) - time()) < 0 || !$user)
        {
            $session = new Session();
            $session->getFlashBag()->set('invalidToken', 'Error, Invalid Token!');
            return $this->redirect('/');
        }

        $columns = [
            'name', 'email', 'created_at',
        ];
        $users = User::all($columns)->toArray();
        return ($type === 'json') ? $this->responseAsJSON($users) : $this->responseAsXML($users);
    }

    public function generateToken()
    {
        $service = new UserService();
        $auth = new Auth();
        $api_token = $auth->getUser()->api_token;
        if(!$api_token)
        {
            $token = $service->generateCode();
            $auth->getUser()->api_token = $token;
            $auth->getUser()->token_end = date('Y-m-d-H:i:s', time() + 60 * 60);
            $auth->getUser()->save();
        }
        return $this->redirect('/');
    }
}
