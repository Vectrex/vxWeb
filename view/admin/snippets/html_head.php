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
    <script src="https://unpkg.com/vue@next"></script>
<?php else: ?>
<script src="https://unpkg.com/vue@3.0.11/dist/vue.global.prod.js"></script>
<?php endif; ?>
<script src="<?= \vxPHP\Application\Application::getInstance()->asset('js/vue/vxweb.umd.js') ?>"></script>
