<!-- { extend: admin/layout_dialog.php @ content_block } -->

<script type="text/javascript" src="/js/admin/doFiles.js"></script>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(this.vxWeb.doFiles);
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
					<th class="m right sortableHeader">Erstellt</th>
					<th class="m"></th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td></td>
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
