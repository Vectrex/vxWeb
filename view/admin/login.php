<!-- { extend: admin/layout_without_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doLogin.js"></script>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(this.vxWeb.doLogin);
</script>

<div id="adminLogin">
	<p style="text-align: right; color: #808080; font-size: 0.7em; padding: 0em 8px; margin: 0;">realisiert mit vxPHP/vxJS, &copy;2007- <?php echo date('Y'); ?> <a href="http://vxweb.net">vxWEB</a></p>
	<?php echo $tpl->form; ?>
	<p style="text-align: right; color: #808080; font-size: 0.7em; padding: 0 8px; margin: 0;">Zurück zu <a href="/"> <strong><?php echo $_SERVER['HTTP_HOST']; ?></strong></a></p>
</div>

<div id="messageBox">&nbsp;</div>