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

use eftec\bladeone\BladeOne;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/*
 * Some blade settings
 */
$views = dirname(__DIR__,1) . '/resources/views';
$cache = dirname(__DIR__,1) . '/storage/cache';
$blade = new BladeOne($views,$cache,BladeOne::MODE_DEBUG); // MODE_DEBUG allows to pinpoint troubles.

$request = Request::createFromGlobals();

//echo $blade->run("auth.register",array("variable1"=>"value1"));
$response = new Response(
    'Content',
    Response::HTTP_OK,
    ['content-type' => 'text/html']
);

$response->send();