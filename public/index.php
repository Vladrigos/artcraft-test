<?php
/*
 * Settings
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
chdir(dirname(__DIR__));
/*
 *  Register The Auto Loader
 */
require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Http\Kernel;

$session = new Session();
$session->start();

$request = Request::createFromGlobals();

$app = new Kernel($request);
$response = $app->handle();

$response->send();