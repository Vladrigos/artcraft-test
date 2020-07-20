<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers(string $type, string $key)
    {
        $columns = [
            'name', 'email', 'created_at',
        ];
        $users = User::all($columns)->toArray();
        return ($type === 'json') ? $this->responseAsJSON($users) : $this->responseAsXML($users);
    }
}