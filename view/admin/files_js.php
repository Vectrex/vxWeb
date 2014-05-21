<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/fileManager.js"></script>

<script type="text/javascript">
	this.vxWeb.routes.files		= "<?php echo vxPHP\Http\Router::getRoute('filesXhr',	'admin.php')->getUrl(); ?>";
	this.vxWeb.routes.upload	= "<?php echo vxPHP\Http\Router::getRoute('uploadXhr',	'admin.php')->getUrl(); ?>";

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

	<div class="buttonBar"><span class="buttonGroup" id="directoryBar"></span></div>

	<div id="filesList">
		<table class="list pct_100">
			<thead>
				<tr>
					<th class="sortableHeader">Dateiname</th>
					<th class="sm right sortableHeader">Größe</th>
					<th class="sm center sortableHeader">Typ/Vorschau</th>
					<th class="mml right sortableHeader">Erstellt</th>
					<th class="m"></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td>Der Dateimanager ist auf JavaScript Unterstützung angewiesen.</td>
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
