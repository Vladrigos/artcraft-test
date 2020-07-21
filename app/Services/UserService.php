<?php

namespace App\Services;

use App\Components\Validator\Validator;

class UserService
{
    public function getSort($querySort = ''): string
    {
        $sort = 'name';
        if (($querySort === 'name') || ($querySort === 'email'))
        {
            $sort = $querySort;
        }

        return $sort;
    }

    public function getOrder($queryOrder = ''): string
    {
        $order = 'desc';
        if (($queryOrder === 'desc') || ($queryOrder === 'asc'))
        {
            $order = $queryOrder;
        }

        return $order;
    }

    public function generateCode(int $length = 24): string
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length)
        {
            $code .= $chars[mt_rand(0, $clen)];
        }

        return $code;
    }

    public function validate($name, $email, $password, $photo, $serverCaptcha, $userCaptcha): array
    {
        $validator = new Validator();

        $errors = $validator->validate([
            $name  => 'name',
            $email => 'email',
            $photo => 'image',
        ]);

        if ($serverCaptcha != $userCaptcha)
        {
            $errors['captcha'][] = "wrong captcha";
        }

        return $errors;
    }
}