<?php

namespace App\Http\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use eftec\bladeone\BladeOne;
use App\Models\User;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

use Symfony\Component\HttpFoundation\Session\Session;

use App\Components\Validator;

class UserController extends Controller
{
    /*
     * GET /
     */
    public function index()
    {
        $request = Request::createFromGlobals();

        $sort = 'name';
        $order = 'desc';
        $querySort = $request->query->get('sort');
        $queryOrder = $request->query->get('order');
        if (($querySort === 'name') || ($querySort === 'email'))
        {
            $sort = $querySort;
        }
        if (($queryOrder === 'desc') || ($queryOrder === 'asc'))
        {
            $order = $queryOrder;
        }

        $users = User::orderBy($sort, $order)->get();

        return $this->render('users.index', ['users' => $users]);
    }

    /*
     * GET /register
     */
    public function create()
    {
        $builder = new CaptchaBuilder();
        $builder->build();

        $captcha = $builder->getPhrase();

        $session = new Session();
        $session->getFlashBag()->set('captcha', $captcha);

        $csrf = new VerifyCsrfToken();
        $csrf = $csrf->getToken();

        return $this->render('auth.register',
            ['builder'    => $builder,
             'session'    => $session,
             'csrf_token' => $csrf,
            ]);
    }

    /*
     * POST /register
     */
    public function store() //array $data
    {
        $request = Request::createFromGlobals();
        $session = new Session();

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $photo = $request->files->get('photo');

        $serverCaptcha = mb_strtolower($session->getFlashBag()->get('captcha')[0]);
        $userCaptcha = mb_strtolower($request->request->get('captcha'));

        $validator = new Validator\Validator();

        $errors = $validator->validate([
            $name  => 'name',
            $email => 'email',
            $photo => 'image',
        ]);

        if ($serverCaptcha != $userCaptcha)
        {
            $errors['captcha'][] = "bad captcha";
        }

        if ($errors)
        {
            $session->getFlashBag()->setAll([
                'errors' => $errors,
                'name'   => $name,
                'email'  => $email
            ]);

            return $this->redirect('/register');
        }

        //save the image
        $imageName = uniqid($name) . ".{$photo->getClientOriginalExtension()}";
        $photo->move(getcwd() . '/public/uploads', $imageName);

        $password = md5($password);
        //вынести отсюда
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < 18)
        {
            $code .= $chars[mt_rand(0, $clen)];
        }

        //create field in database
        User::create([
            'name'      => $name,
            'email'     => $email,
            'photo'     => $imageName,
            'password'  => $password,
            'user_hash' => $code,
        ]);
        $response = new RedirectResponse('/');
        $response->headers->setCookie(Cookie::create('user_hash', $code, strtotime('+1 hour')));
        return $response;
//       return $this->redirect('/');
    }

    public function show($id)
    {
        try
        {
            $user = User::where('id', $id)->firstOrFail();
            return $this->render('users.show', ['user' => $user]);
        }
        catch (ModelNotFoundException $e)
        {
            throw new ResourceNotFoundException();
        }
    }

    public function login()
    {
        $csrf = new VerifyCsrfToken();
        $csrf = $csrf->getToken();

        return $this->render('auth.login', ['csrf_token' => $csrf]);
    }

    public function loginPost()
    {
        $request = Request::createFromGlobals();

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        if ($user = User::where('password', md5($password))
            ->where('email', $email)
            ->first())
        {
            //generate hash
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
            $code = "";
            $clen = strlen($chars) - 1;
            while (strlen($code) < 18)
            {
                $code .= $chars[mt_rand(0, $clen)];
            }

            $response = new RedirectResponse('/');
            $response->headers->setCookie(Cookie::create('user_hash', $code, strtotime('+1 hour')));
            return $response;
        }

        return $this->redirect('/');
    }
}