<!-- { extend: admin/layout_without_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doLogin.js"></script>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(this.vxWeb.doLogin);
</script>

<div id="wrapper" style="margin-top: 10em; padding: 2em 0; background: #9df; box-shadow: 0 0 1em #444 inset; border-top: solid 1px #aef; border-bottom: solid 1px #aef;">
	<div id="adminLogin">
		<p style="text-align: right; font-size: 80%; padding: 0em 0.5em; margin: 0;">realisiert mit <a href="http://vxweb.net">vxWeb</a> &copy;2007- <?php echo date('Y'); ?></p>
		<?php echo $tpl->form; ?>
		<p style="text-align: right; font-size: 80%; padding: 0 0.5em; margin: 0;">Zurück zu <a href="/"><?php echo $_SERVER['HTTP_HOST']; ?></a></p>
	</div>
</div>

<div id="messageBox">&nbsp;</div>