<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/fileManager.js"></script>

<script type="text/javascript">
	this.vxWeb.routes.files		= "<?php echo vxPHP\Routing\Router::getRoute('filesXhr',	'admin.php')->getUrl(); ?>";
	this.vxWeb.routes.upload	= "<?php echo vxPHP\Routing\Router::getRoute('uploadXhr',	'admin.php')->getUrl(); ?>";

	vxJS.event.addDomReadyListener(function() {
		vxWeb.fileManager({
			directoryBar:		document.getElementById("directoryBar"),
			filesList:			document.getElementById("filesList"),
			uploadMaxFilesize:	<?php echo $this->upload_max_filesize; ?>,
			maxUploadTime:		<?php echo $this->max_execution_time_ms; ?>
		});
	});
</script>

<h1>Dateien</h1>

<div id="files">

	<span class="btn-group" id="directoryBar"></span>

	<div id="filesList">
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="vx-sortable-header">Dateiname</th>
					<th class="vx-sortable-header">Größe</th>
					<th class="vx-sortable-header">Typ/Vorschau</th>
					<th class="vx-sortable-header">Erstellt</th>
					<th class=""></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>

</div>
