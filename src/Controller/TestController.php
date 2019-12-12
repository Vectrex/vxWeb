<?php


namespace App\Controller;


use vxPHP\Controller\Controller;
use vxPHP\Http\Response;

class TestController extends Controller
{

    /**
     * the actual controller functionality implemented in the individual controllers
     *
     * @return Response, JsonResponse
     */
    protected function execute()
    {

    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        $name = $this->route->getPathParameter('name');

        return new Response('Hallo ' . $name);
    }
}