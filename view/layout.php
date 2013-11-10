<!doctype html>

<html>
	<head>
		<title>vxWeb - Layout Template</title>
		<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
		<meta name='author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>
		<link type='text/css' rel='stylesheet' href='/css/default.css'>
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
