<!-- { extend: admin/layout_without_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doLogin.js"></script>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(this.vxWeb.doLogin);
</script>

<div id="login" class="modal active">
	<div class="modal-container">
		<div class="modal-header">realisiert mit <a href="https://vxweb.net">vxWeb</a> &copy;2007- <?php echo date('Y'); ?></div>
        <div class="modal-body"><?php echo $tpl->form; ?></div>
		<div class="modal-footer">Zurück zu <a href="/"><?php echo $_SERVER['HTTP_HOST']; ?></a></div>
	</div>
</div>
