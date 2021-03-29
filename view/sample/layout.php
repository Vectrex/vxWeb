<!doctype html>
<html lang="<?= \vxPHP\Application\Application::getInstance()->getConfig()->site->default_locale ?? '' ?>">
	<head>
		<title>vxWeb</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name='web-author' content="Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
		<link type="text/css" rel="stylesheet" href="<?= \vxPHP\Application\Application::getInstance()->asset('css/site.min.css') ?>">
        <!-- { block: header_block } -->
	</head>

	<body>
		<div id="page">

			<div id="main">

				<div id="content" class="p-2">
				<!-- { block: content_block } -->
				</div>
			</div>

			<div id="footer"></div>
		</div>
	</body>
</html>
