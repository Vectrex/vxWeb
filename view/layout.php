<!doctype html>

<html>
	<head>
		<title>vxWeb - Layout Template</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name='web-author' content="Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
		<link type="text/css" rel="stylesheet" href="/css/default.css">
	</head>

	<body>
		<div id="page">

			<div id="main">
				<?php echo vxPHP\Webpage\MenuGenerator::create('main', 0)->render(); ?>

				<div id="content">
				<!-- { block: content_block } -->
				</div>
			</div>

			<div id="footer"></div>
		</div>
	</body>
</html>
