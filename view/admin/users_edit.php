<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doUsers.js"></script>

<script type="text/javascript">
	if(!this.vxWeb.parameters) {
		this.vxWeb.parameters = {};
	}
	if(!this.vxWeb.serverConfig) {
		this.vxWeb.serverConfig = {};
	}
	
	
	this.vxWeb.routes.users			= "<?php echo vxPHP\Routing\Router::getRoute('usersXhr', 'admin.php')->getUrl(); ?>?<?php echo vxPHP\Http\Request::createFromGlobals()->getQueryString(); ?>";
	this.vxWeb.parameters.usersId	= <?php echo vxPHP\Http\Request::createFromGlobals()->query->getInt('id'); ?>;

	vxJS.event.addDomReadyListener(function() {
		vxWeb.doUsers();
	});

</script>

<h1>User <em class="smaller"><?php echo $tpl->user->getName(); ?></em></h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe025;" href="$users">Zurück zur Übersicht</a>
</div>

<?php echo $this->form; ?>
