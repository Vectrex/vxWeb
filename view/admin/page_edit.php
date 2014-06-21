<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<?php if($tpl->allow_nice_edit): ?>

	<?php $url = vxPHP\Routing\Router::getRoute('filepicker', 'admin.php')->getUrl(); ?>

	<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

	<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		CKEDITOR.replace(document.forms[0].elements['Markup'], {
			extraAllowedContent: "div(*)",
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
				height: "450px", contentsCss: '/css/default.css',
				filebrowserBrowseUrl: "<?php echo $url; ?>",
				filebrowserImageBrowseUrl: "<?php echo $url; ?>?filter=image"
			} );
	});
	</script>

<?php endif; ?>

<h1>Seiten</h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe025;" href="$pages">Zurück zur Übersicht</a>
</div>

<?php echo $tpl->form; ?>
