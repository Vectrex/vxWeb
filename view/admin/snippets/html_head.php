<title>vxWeb Admin</title>
<meta name='keywords' content=''>
<meta name='description' content=''>
<meta name='robots' content='noindex, nofollow'>
<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
<meta name='web-author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>
<meta name="csrf-token" content="<?= (new \vxPHP\Security\Csrf\CsrfTokenManager())->refreshToken('admin') ?>">
<link rel='icon' type='image/x-icon' href='/favicon.ico'>
<link type='text/css' rel='stylesheet' href='<?= \vxPHP\Application\Application::getInstance()->asset('css/admin.min.css') ?>'>
<?php if(\vxPHP\Application\Application::getInstance()->runsLocally()): ?>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<?php else: ?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.11"></script>
<?php endif; ?>
<script src="<?= \vxPHP\Application\Application::getInstance()->asset('js/vue/vxweb.umd.min.js') ?>"></script>
