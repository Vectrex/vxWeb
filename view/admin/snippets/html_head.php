<title>vxWeb Admin</title>
<meta name='keywords' content=''>
<meta name='description' content=''>
<meta name='robots' content='noindex, nofollow'>
<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
<meta name='web-author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>
<meta name="csrf-token" content="<?= (new \vxPHP\Security\Csrf\CsrfTokenManager())->refreshToken('admin') ?>">
<link rel='icon' type='image/x-icon' href='/favicon.ico'>
<link type='text/css' rel='stylesheet' href='<?= \vxPHP\Application\Application::getInstance()->asset('css/admin.min.css') ?>'>
<script type="text/javascript" src="<?= \vxPHP\Application\Application::getInstance()->asset('js/admin/vxjs.js') ?>"></script>
