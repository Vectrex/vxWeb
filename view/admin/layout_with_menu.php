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
		<link type='text/css' rel='stylesheet'	href='/css/admin_site_specific.css'>

		<script type='text/javascript' src='/js/core.js'></script>
		<script type='text/javascript' src='/js/widget.js'></script>
		<script type='text/javascript' src='/js/xhr.js'></script>
		<script type='text/javascript' src='/js/extra/fx.js'></script>
		<script type='text/javascript' src='/js/extra/dnd.js'></script>
		<script type='text/javascript' src='/js/widgets/xhrform.js'></script>
		<script type='text/javascript' src='/js/widgets/confirm.js'></script>
		<script type='text/javascript' src='/js/widgets/calendar.js'></script>
		<script type='text/javascript' src='/js/widgets/sortable.js'></script>
		<script type='text/javascript' src='/js/widgets/tree.js'></script>
		<script type='text/javascript' src='/js/widgets/simpletabs.js'></script>

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
				<p>
					<span class="userInfo">Angemeldet <strong><?php echo vxPHP\User\Admin::getInstance()->getName(); ?></strong> (<?php echo vxPHP\User\Admin::getInstance()->getId(); ?>)</span> &bull;
					<a class="profileLink" href="/<?php echo vxPHP\Http\Router::getRoute('profile', 'admin.php')->getUrl(); ?>">Meine Einstellungen</a> &bull;
					<a class="logoutLink" href="/<?php echo vxPHP\Http\Router::getRoute('logout', 'admin.php')->getUrl(); ?>">Abmelden</a> &bull;
					Gehe zu <a href="/" class="homeLink"><?php echo vxPHP\Http\Request::createFromGlobals()->getHost(); ?></a>
				</p>
			</div>

			<?php echo vxPHP\Webpage\MenuGenerator::create('admin', 0)->render(); ?>

			<div id="content">
				<!-- { block: content_block } -->
			</div>

		</div>

		<div id="messageBox">&nbsp;</div>

	</body>
</html>
