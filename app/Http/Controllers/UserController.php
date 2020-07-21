<?php

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Csrf;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{
    private UserService $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new UserService;
    }

    /*
     * GET /
     */
    public function index()
    {
        $request = Request::createFromGlobals();
        $sort = $this->service->getSort($request->query->get('sort'));
        $order = $this->service->getOrder($request->query->get('order'));

        $users = User::orderBy($sort, $order)->get();
        $auth = new Auth();
        $swappedOrder = ($order === 'desc') ? 'asc' : 'desc';
        return $this->render('users.index', ['users' => $users, 'auth' => $auth, 'swappedOrder' => $swappedOrder]);
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

        $csrf = new Csrf();
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
    public function store()
    {
        $request = Request::createFromGlobals();
        $session = new Session();

        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $photo = $request->files->get('photo');
        $serverCaptcha = mb_strtolower($session->getFlashBag()->get('captcha')[0]);
        $userCaptcha = mb_strtolower($request->request->get('captcha'));

        if ($errors = $this->service->validate($name, $email, $password, $photo, $serverCaptcha, $userCaptcha))
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

        $code = $this->service->generateCode();

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
}