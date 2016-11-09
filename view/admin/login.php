<!-- { extend: admin/layout_without_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doLogin.js"></script>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(this.vxWeb.doLogin);
</script>

<div id="login">
	<div>
		<div>realisiert mit <a href="http://vxweb.net">vxWeb</a> &copy;2007- <?php echo date('Y'); ?></div>
		<?php echo $tpl->form; ?>
		<div>Zurück zu <a href="/"><?php echo $_SERVER['HTTP_HOST']; ?></a></div>
	</div>
</div>

<div id="messageBox">&nbsp;</div>