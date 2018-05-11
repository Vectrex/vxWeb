<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/admin/fileManager.js"></script>

<script type="text/javascript">
	this.vxWeb.routes.files		= "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('filesXhr')->getUrl() ?>";
	this.vxWeb.routes.upload	= "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('uploadXhr')->getUrl() ?>";

	vxJS.event.addDomReadyListener(function() {
		vxWeb.fileManager({
			directoryBar:		document.getElementById("directoryBar"),
			filesList:			document.getElementById("filesList"),
			uploadMaxFilesize:	<?= $this->upload_max_filesize ?>,
			maxUploadTime:		<?= $this->max_execution_time_ms ?>
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
        <button id="addFolder" class="btn with-webfont-icon-right btn-primary" type="button" data-icon="&#xe007;">Verzeichnis anlegen</button>
        <button id="addFile" class="btn with-webfont-icon-right btn-primary" type="button" data-icon="&#xe00e;">Datei hinzufügen</button>
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
