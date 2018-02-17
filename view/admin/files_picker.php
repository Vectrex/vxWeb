<!-- { extend: admin/layout_dialog.php @ content_block } -->

<script type="text/javascript" src="/js/admin/fileManager.js"></script>

<script type="text/javascript">

	this.vxWeb.routes.files		= "<?php echo vxPHP\Routing\Router::getRoute('filepickerXhr', 'admin.php')->getUrl(); ?>";
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

<div class="navbar">
    <div class="navbar-section">
        <span class="btn-group" id="directoryBar"></span>
    </div>

    <div class="navbar-section">
        <div id="uploadProgress" class="col-3 vx-progress-bar tooltip">
            <div class="bar">
                <div class="bar-item"></div>
            </div>
        </div>
        <span id="activityIndicator" class="vx-activity-indicator"></span>
        <input id="addFolderInput" class="form-input col-3" style="display: none;">
        <button id="addFolder" class="btn with-webfont-icon-right" type="button" data-icon="&#xe007;">Verzeichnis anlegen</button>
        <button id="addFile" class="btn with-webfont-icon-right" type="button" data-icon="&#xe00e;">Datei hinzufügen</button>
    </div>
</div>

<div id="filesList">
    <table class="table table-striped">
        <thead>
        <tr>
            <th class="vx-sortable-header">Dateiname</th>
            <th class="col-1 vx-sortable-header">Größe</th>
            <th class="col-2 vx-sortable-header">Typ/Vorschau</th>
            <th class="col-2 vx-sortable-header">Erstellt</th>
            <th class="col-2"></th>
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
