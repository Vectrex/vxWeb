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

		<h id="page">
			<header class="navbar p-2">
                <section class="navbar-section">
					<span class="userInfo">Angemeldet <strong><?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername() ?></strong> (<?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getAttribute('email') ?>)</span>
                </section>
                <section class="navbar-section">
					<a class="withIcon" data-icon="&#xe009;" href="<?= vxPHP\Routing\Router::getRoute('profile', 'admin.php')->getUrl(); ?>">Meine Einstellungen</a> &bull;
					<a  class="withIcon" data-icon="&#xe021;" href="<?= vxPHP\Routing\Router::getRoute('logout', 'admin.php')->getUrl(); ?>">Abmelden</a> &bull;
					Gehe zu <a href="/" class="withIcon" data-icon="&#xe000;"><?= vxPHP\Http\Request::createFromGlobals()->getHost() ?></a>
                </section>
            </header>

            <?= vxPHP\Webpage\MenuGenerator::create('admin', 0, null, null, ['ulClass' => 'menu', 'liClass' => 'menu-item', 'containerTag' => ''])->render() ?>

			<div id="content">
				<!-- { block: content_block } -->
			</div>

		</div>

		<div id="messageBox">&nbsp;</div>

	</body>
</html>
