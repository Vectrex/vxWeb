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
		<link type='text/css' rel='stylesheet' href='/css/admin.min.css'>
        <!-- <script type="text/javascript" src="/js/admin/vxjs.js"></script> -->
        <script type="text/javascript" src="/js/vendor/vxJS/core.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/xhr.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widget.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/xhrform.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/sortable.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/simpletabs.js"></script>
        <script type="text/javascript" src="/js/vendor/vxJS/widgets/calendar.js"></script>


        <script type="text/javascript">
			if(!this.vxWeb) {
				this.vxWeb = {};
			}
			if(!this.vxWeb.routes) {
				this.vxWeb.routes = {};
			}

            vxWeb.messageToast = function(selector) {

                var mBox, lastAddedClass, timeoutId, button;

                var hide = function() {
                    if(mBox) {
                        mBox.classList.remove("display");
                    }
                };

                var show = function(msg, className) {

                    if(mBox === undefined) {
                        mBox = document.querySelector(selector || "#messageBox");

                        if(mBox && (button = mBox.querySelector("button"))) {
                            button.addEventListener("click", hide);
                        }
                    }

                    if(mBox) {
                        if(lastAddedClass) {
                            mBox.classList.remove(lastAddedClass);
                        }
                        if(className) {
                            mBox.classList.add(className);
                        }
                        lastAddedClass = className;
                    }

                    mBox.innerHTML = msg;
                    mBox.appendChild(button);
                    mBox.classList.add("display");

                    if(timeoutId) {
                        window.clearTimeout(timeoutId);
                    }
                    timeoutId = window.setTimeout(hide, 5000);

                };

                return {
                    show: show,
                    hide: hide
                };

            };
		</script>

	</head>

	<body>
		<div id="page">
			<header class="vx-navbar p-2">
                <section class="navbar-section">
                    <button class="btn btn-link webfont-icon-only" onclick="document.getElementById('sidebar').classList.toggle('hidden');">&#xe054;</button>
					<a class="btn btn-link with-webfont-icon-right" data-icon="&#xe010;" href="<?= vxPHP\Routing\Router::getRoute('profile', 'admin.php')->getUrl(); ?>"><strong><?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername() ?></strong> (<?= vxPHP\Application\Application::getInstance()->getCurrentUser()->getAttribute('email') ?>)</a>
                </section>
                <section class="navbar-section">
                    <a  class="btn btn-link with-webfont-icon-right" data-icon="&#xe012;" href="<?= vxPHP\Routing\Router::getRoute('logout', 'admin.php')->getUrl(); ?>">Abmelden</a>
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
