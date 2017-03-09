<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/admin/doPages.js"></script>

<script type="text/javascript">
	
	this.vxWeb.routes.page			= "<?php echo vxPHP\Routing\Router::getRoute('pagesXhr', 'admin.php')->getUrl(); ?>?<?php echo vxPHP\Http\Request::createFromGlobals()->getQueryString(); ?>";
	this.vxWeb.routes.filePicker	= "<?php echo vxPHP\Routing\Router::getRoute('filepicker', 'admin.php')->getUrl(); ?>";

</script>

<?php if($tpl->allow_nice_edit): ?>

	<script type="text/javascript">

		vxJS.event.addDomReadyListener(function() {
	
			CKEDITOR.replace(document.forms[0].elements["markup"], {
				allowedContent: true,
				autoParagraph: false,
				// extraAllowedContent: "div(*)",
				toolbar:
					[
					    ['Maximize', '-', 'Source', '-', 'Undo', 'Redo'],
					    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','RemoveFormat'],
					    ['Link','Unlink','Anchor'],
					    ['Image','Table','HorizontalRule','SpecialChar'],
					    '/',
					    ['Format'],
					    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript', '-', 'TextColor','BGColor'],
					    ['NumberedList','BulletedList','-','Blockquote', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
					],
					height: "450px", contentsCss: ['/css/site.css', '/css/site_edit.css'],
					filebrowserBrowseUrl: vxWeb.routes.filePicker,
					filebrowserImageBrowseUrl: vxWeb.routes.filePicker + "?filter=image"
				}

			);

		});

	</script>

<?php endif; ?>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		vxWeb.doPages();
	});
</script>

<h1>Seiten</h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe025;" href="$pages">Zurück zur Übersicht</a>
</div>

<?php echo $tpl->form; ?>
