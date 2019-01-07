<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 07.01.19
 * Time: 12:13
 */

namespace App\Controller\Sample;


use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Template\SimpleTemplate;

class TemplateController extends Controller
{

    /**
     * @return Response, JsonResponse
     */
    protected function execute()
    {
        return new Response(
            SimpleTemplate::create('sample/template.php')
                ->assign('explanation', 'vxWeb uses a very simple templating mechanism.')
                ->display()
        );
    }
}