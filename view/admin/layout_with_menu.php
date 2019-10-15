<!doctype html>

<html>
	<head>

		<title>vxWeb Admin</title>
		<meta name='keywords' content=''>
		<meta name='description' content=''>
		<meta name='robots' content='noindex, nofollow'>

		<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
		<meta name='author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>

		<link rel='icon' type='image/x-icon' href='/favicon.ico'>
		<link type='text/css' rel='stylesheet' href="<?= \vxPHP\Application\Application::getInstance()->asset('css/admin.min.css') ?>">
        <script type="text/javascript" src="<?= \vxPHP\Application\Application::getInstance()->asset('js/admin/vxjs.js') ?>"></script>
        <!--
        <script type="text/javascript" src="/js/vendor/vxJS/core.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/xhr.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widget.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/xhrform.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/sortable.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/simpletabs.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/calendar.js"></script>
        -->

        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

	</head>

	<body>
		<div id="page">
			<header class="vx-navbar p-2">
                <section class="navbar-section">
                    <button class="btn btn-link webfont-icon-only" onclick="document.getElementById('sidebar').classList.toggle('hidden');">&#xe054;</button>
					<a class="btn btn-link with-webfont-icon-right" data-icon="&#xe010;" href="<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile')->getUrl(); ?>"><strong><?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername() ?></strong> (<?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getAttribute('email') ?>)</a>
                </section>
                <section class="navbar-section">
                    <a  class="btn btn-link with-webfont-icon-right" data-icon="&#xe012;" href="<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('logout')->getUrl(); ?>">Abmelden</a>
                    <a href="/" target="_blank" class="btn btn-link with-webfont-icon-right" data-icon="&#xe023;">Gehe zu <?= vxPHP\Http\Request::createFromGlobals()->getHost() ?></a>
                </section>
            </header>

            <div class="columns">
                <div id="sidebar" class="column">
                    <?= vxPHP\Webpage\MenuGenerator::create('admin', 0, null, null, ['ulClass' => 'nav', 'liClass' => 'nav-item', 'containerTag' => ''])->render() ?>
                </div>

                <div id="content" class="column">
                    <!-- { block: content_block } -->
                </div>
            </div>
            <div id="messageBox" class="toast"><button class="btn btn-clear float-right"></button></div>
		</div>
    </body>
</html>
