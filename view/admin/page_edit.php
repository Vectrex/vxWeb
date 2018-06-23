<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/admin/doPages.js"></script>

<script type="text/javascript">
	
	this.vxWeb.routes.page			= "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('pagesXhr')->getUrl() ?>?<?= vxPHP\Http\Request::createFromGlobals()->getQueryString() ?>";
	this.vxWeb.routes.filePicker	= "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('filepicker')->getUrl() ?>";

</script>

<?php if($tpl->allow_nice_edit): ?>

	<script type="text/javascript">

		vxJS.event.addDomReadyListener(function() {
	
			CKEDITOR.replace(document.forms[0].elements["markup"], {
				allowedContent: true,
				autoParagraph: false,
				customConfig: "",
				toolbar:
					[
					    ['Maximize', '-', 'Source'],
					    ['Undo', 'Redo', '-', 'Cut','Copy','Paste','PasteText','PasteFromWord'],
                        [ 'Find', 'Replace'],
                        [ 'Bold', 'Italic', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat', '-', 'TextColor', 'BGColor'],
                        ['NumberedList','BulletedList','-','Blockquote'],
					    ['Link','Unlink'],
					    ['Image','Table','SpecialChar'],
					    ['Styles', 'Format'],
                        ['ShowBlocks']
					],
					height: "24rem", contentsCss: ['/css/site.css', '/css/site_edit.css'],
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

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="$pages">Zurück zur Übersicht</a>
</div>

<?php echo $tpl->form; ?>
