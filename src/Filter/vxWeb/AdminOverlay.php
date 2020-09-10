<?php

namespace App\Filter\vxWeb;

use vxPHP\Application\Application;
use vxPHP\Http\Request;
use vxPHP\Routing\Router;
use vxPHP\Template\Filter\SimpleTemplateFilter;
use vxPHP\Template\Filter\SimpleTemplateFilterInterface;
use vxPHP\Template\SimpleTemplate;

/**
 * Class AdminOverlay
 * @package App\Filter\vxWeb
 */
class AdminOverlay extends SimpleTemplateFilter implements SimpleTemplateFilterInterface {

    const OVERLAY_HEAD_TPL = <<<'EOD'
    <link type="text/css" rel="stylesheet" href="/css/adminoverlay.min.css">
EOD;

    /**
     * add a fixed toolbar at the bottom
     *
     * @param string $templateString
     * @throws \vxPHP\Application\Exception\ApplicationException
     * @throws \vxPHP\Template\Exception\SimpleTemplateException
     */
    public function apply(&$templateString): void
    {
        $app = Application::getInstance();
        $user = $app->getCurrentUser();

        if(
            $user &&
            $user->isAuthenticated() &&
            $user->hasRole('superadmin') &&
            'index.php' === $app->getCurrentRoute()->getScriptName()
        ) {

            $router = new Router(Application::getInstance()->getConfig()->routes['admin.php']);

            $currentRoute = $app->getCurrentRoute();

            $templateString = preg_replace(
                '~</head>~i',
                self::OVERLAY_HEAD_TPL . '</head>',
                $templateString
            );

            $tpl = SimpleTemplate::create('admin/snippets/admin_overlay.php')
                ->assign('logout_route', $router->getRoute('logout')->getUrl() . '?goto=' . urlencode(Request::createFromGlobals()->getBaseUrl()))
                ->assign('admin_route', $router->getRoute('pages')->getUrl())
                ->blockFilter('admin_overlay')
            ;

            $templateString = preg_replace(
                '~</body>~i',
                $tpl->display() . '</body>',
                $templateString
            );
        }
    }
}