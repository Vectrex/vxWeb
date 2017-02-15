<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doUsers.js"></script>

<script type="text/javascript">
	if(!this.vxWeb.parameters) {
		this.vxWeb.parameters = {};
	}
	if(!this.vxWeb.serverConfig) {
		this.vxWeb.serverConfig = {};
	}
	
	
	this.vxWeb.routes.users			= "<?= vxPHP\Routing\Router::getRoute('usersXhr', 'admin.php')->getUrl() ?>?<?= vxPHP\Http\Request::createFromGlobals()->getQueryString() ?>";
	this.vxWeb.parameters.usersId	= "<?= vxPHP\Http\Request::createFromGlobals()->query->get('id') ?: '' ?>";

	vxJS.event.addDomReadyListener(function() {
		vxWeb.doUsers();
	});

</script>

<h1>User <em class="smaller"><?= $tpl->user ? $tpl->user['name'] : 'neuer User' ?></em></h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe025;" href="$users">Zurück zur Übersicht</a>
</div>

<?= $this->form ?>
