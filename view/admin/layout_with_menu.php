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
		<link type='text/css' rel='stylesheet'	href='/css/vxweb.css'>
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

		<div id="page">
			<div id="statusBar">
					<span class="userInfo">Angemeldet <strong><?php echo vxPHP\User\User::getSessionUser()->getName(); ?></strong> (<?php echo vxPHP\User\User::getSessionUser()->getId(); ?>)</span> &bull;
					<a class="withIcon" data-icon="&#xe009;" href="<?php echo vxPHP\Routing\Router::getRoute('profile', 'admin.php')->getUrl(); ?>">Meine Einstellungen</a> &bull;
					<a  class="withIcon" data-icon="&#xe021;" href="<?php echo vxPHP\Routing\Router::getRoute('logout', 'admin.php')->getUrl(); ?>">Abmelden</a> &bull;
					Gehe zu <a href="/" class="withIcon" data-icon="&#xe000;"><?php echo vxPHP\Http\Request::createFromGlobals()->getHost(); ?></a>
			</div>

			<?php echo vxPHP\Webpage\MenuGenerator::create('admin', 0)->render(); ?>

			<div id="content">
				<!-- { block: content_block } -->
			</div>

		</div>

		<div id="messageBox">&nbsp;</div>

	</body>
</html>
