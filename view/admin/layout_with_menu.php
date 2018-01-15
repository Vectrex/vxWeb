<!doctype html>

<html>
	<head>

		<title>vxWeb Admin</title>
		<meta name='keywords' content=''>
		<meta name='description' content=''>
		<meta name='robots' content='noindex, nofollow'>

		<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
		<meta name='author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>

		<link rel='icon' type='image/x-icon'	href='/favicon.ico'>
		<link type='text/css' rel='stylesheet'	href='/css/admin.css'>

		<script type='text/javascript' src='/js/vendor/vxJS/core.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widget.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/xhr.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/extra/fx.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/extra/dnd.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widgets/xhrform.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widgets/confirm.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widgets/calendar.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widgets/sortable.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widgets/tree.js'></script>
		<script type='text/javascript' src='/js/vendor/vxJS/widgets/simpletabs.js'></script>

		<script type="text/javascript">
			if(!this.vxWeb) {
				this.vxWeb = {};
			}
			if(!this.vxWeb.routes) {
				this.vxWeb.routes = {};
			}
		</script>

	</head>

	<body>

		<div id="page" class="off-canvas off-canvas-sidebar-show">
			<header class="vxweb-navbar p-2">
                <section class="navbar-section">
					<span class="userInfo">Angemeldet <strong><?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername() ?></strong> (<?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getAttribute('email') ?>)</span>
                </section>
                <section class="navbar-section">
                    <i class="icon icon-people"></i><a class="btn btn-link" href="<?= vxPHP\Routing\Router::getRoute('profile', 'admin.php')->getUrl(); ?>">Meine Einstellungen</a>
                    <i class="icon icon-shutdown"></i><a  class="btn btn-link" href="<?= vxPHP\Routing\Router::getRoute('logout', 'admin.php')->getUrl(); ?>">Abmelden</a>
                    <i class="icon icon-arrow-right"></i><a href="/" class="btn btn-link">Gehe zu <?= vxPHP\Http\Request::createFromGlobals()->getHost() ?></a>
                </section>
            </header>

            <div id="sidebar" class="off-canvas-sidebar vxweb-sidebar">
                <?= vxPHP\Webpage\MenuGenerator::create('admin', 0, null, null, ['ulClass' => 'nav', 'liClass' => 'nav-item', 'containerTag' => ''])->render() ?>
            </div>

			<div id="content" class="off-canvas-content vxweb-content">
				<!-- { block: content_block } -->
                <div id="messageBox">&nbsp;</div>
            </div>

		</div>

	</body>
</html>
