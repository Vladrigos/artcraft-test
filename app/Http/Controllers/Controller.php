<?php

namespace App\Http\Controllers;

use eftec\bladeone\BladeOne;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected Response $response;
    protected BladeOne $blade;

    public function __construct()
    {
        $request = Request::createFromGlobals();
        $this->response = (new Response())->prepare($request);

        $views = dirname(__DIR__, 3) . '/resources/views';
        $cache = dirname(__DIR__, 3) . '/storage/cache';
        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    }

    protected function render(string $view, array $parameters = []): Response
    {
        $html = $this->blade->run($view, $parameters);
        return $this->response->setContent($html);
    }

    protected function redirect(string $url, int $status = 302, array $headers = []): Response
    {
        return new RedirectResponse($url, $status, $headers);
    }

    protected function responseAsJSON($data): Response
    {
        $response = $this->response;
        $response->setContent(json_encode([
            'data' => $data,
        ]));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    protected function responseAsXML($data): Response
    {
        $response = $this->response;

        $array_to_xml = function ($data, &$xml_data) use (&$array_to_xml) {
            foreach ($data as $key => $value)
            {
                if (is_array($value))
                {
                    if (is_numeric($key))
                    {
                        $key = 'item' . $key;
                    }
                    $subnode = $xml_data->addChild($key);
                    $array_to_xml($value, $subnode);
                } else
                {
                    $xml_data->addChild("$key", htmlspecialchars("$value"));
                }
            }
        };

        $xml_data = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
        $array_to_xml($data, $xml_data);

        return $response->setContent($xml_data->asXML());
    }


}