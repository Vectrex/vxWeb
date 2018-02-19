<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/admin/fileManager.js"></script>
<script type="text/javascript" src="/js/admin/doArticles.js"></script>

<script type="text/javascript">
	if(!this.vxWeb.parameters) {
		this.vxWeb.parameters = {};
	}
	if(!this.vxWeb.serverConfig) {
		this.vxWeb.serverConfig = {};
	}
	
	
	this.vxWeb.routes.articles	= "<?= vxPHP\Routing\Router::getRoute('articlesXhr', 'admin.php')->getUrl() ?>?<?= vxPHP\Http\Request::createFromGlobals()->getQueryString() ?>";
	this.vxWeb.routes.files		= "<?= vxPHP\Routing\Router::getRoute('fileincludeXhr', 'admin.php')->getUrl() ?>";
	this.vxWeb.routes.upload	= "<?= vxPHP\Routing\Router::getRoute('uploadXhr', 'admin.php')->getUrl() ?>";

	this.vxWeb.parameters.fileColumns = ["name", "size", "mime", "mTime", "linked"];
	this.vxWeb.parameters.articlesId = <?= vxPHP\Http\Request::createFromGlobals()->query->get('id', 'null') ?>;

	this.vxWeb.serverConfig.uploadMaxFilesize = <?= $this->upload_max_filesize ?>;
	this.vxWeb.serverConfig.maxUploadTime = <?= $this->max_execution_time_ms ?>; 
	
	vxJS.event.addDomReadyListener(function() {
		vxWeb.doArticles();
	});

</script>

<script type="text/javascript">
vxJS.event.addDomReadyListener(function() {
	CKEDITOR.replace(document.forms[0].elements['content'], {
		extraAllowedContent: "div(*)",
		toolbar:
			[
			    ['Maximize','-','Source', '-', 'Undo','Redo'],
			    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord','-','RemoveFormat'],
			    ['Bold', 'Italic', '-', 'NumberedList','BulletedList', '-', 'Table', '-', 'Link', 'Unlink', 'Anchor']
			], height: "20em", width: "52em", contentsCss: ['/css/site.css', '/css/site_edit.css']
		} );

	vxJS.widget.calendar(
		document.getElementsByName("article_date")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[0],
			noYearInput: true,
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
	vxJS.widget.calendar(
		document.getElementsByName("display_from")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[1],
			noYearInput: true,
			noPast: true,
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
	vxJS.widget.calendar(
		document.getElementsByName("display_until")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[2],
			noYearInput: true,
			noPast: true,
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
});
</script>

<h1>Artikel &amp; News <em class="text-smaller"><?= $tpl->title ?></em></h1>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="$<?= $tpl->backlink ?>">Zurück zur Übersicht</a>
</div>

<div class="vxJS_tabThis">

    <div class="section">
        <h2 id="article_content">Inhalt</h2>
        <?= $tpl->article_form ?>
    </div>

    <div class="section">
        <h2 id="article_files">Dateien</h2>

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
                <button id="addFolder" class="btn webfont-icon-only tooltip" data-tooltip="Verzeichnis anlegen" type="button">&#xe007;</button>
                <button id="addFile" class="btn webfont-icon-only tooltip" type="button" data-tooltip="Datei hinzufügen">&#xe00e;</button>
                <!-- <button id="sortFiles" class="btn webfont-icon-only tooltip tooltip-left" type="button" data-tooltip="Verlinkte Dateien sortieren">&#xe035;</button> -->
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
                        <th class="col-1">Link</th>
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
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="section">
        <h2 id="sort_article_files">Sortierung verlinkter Dateien</h2>

        <table class="table table-striped" id="linkedFilesTable">
            <thead>
                <tr>
                    <th></th>
                    <th>Typ</th>
                    <th>Dateiname</th>
                    <th>Ordner</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!--
<div class="calendar">
    <div class="calendar-nav navbar">
        <button class="btn btn-action btn-link btn-lg">
            <i class="icon icon-arrow-left"></i>
        </button>
        <div class="navbar-primary">March 2017</div>
        <button class="btn btn-action btn-link btn-lg">
            <i class="icon icon-arrow-right"></i>
        </button>
    </div>
    <div class="calendar-container">
        <div class="calendar-header">
            <div class="calendar-date">Sun</div>
            <div class="calendar-date">Mon</div>
            <div class="calendar-date">Tue</div>
            <div class="calendar-date">Wed</div>
            <div class="calendar-date">Thu</div>
            <div class="calendar-date">Fri</div>
            <div class="calendar-date">Sat</div>
        </div>
        <div class="calendar-body">
            <div class="calendar-date prev-month disabled"><button class="date-item">26</button></div>
            <div class="calendar-date prev-month disabled"><button class="date-item">27</button></div>
            <div class="calendar-date prev-month disabled"><button class="date-item">28</button></div>
            <div class="calendar-date current-month"><button class="date-item">1</button></div>
            <div class="calendar-date current-month"><button class="date-item">2</button></div>
            <div class="calendar-date current-month"><button class="date-item">3</button></div>
            <div class="calendar-date current-month tooltip" data-tooltip="Today"><button class="date-item date-today">4</button></div>
            <div class="calendar-date current-month tooltip" data-tooltip="Not available"><button class="date-item" disabled="">5</button></div>
            <div class="calendar-date current-month"><button class="date-item">6</button></div>
            <div class="calendar-date current-month"><button class="date-item">7</button></div>
            <div class="calendar-date current-month tooltip" data-tooltip="You have appointments"><button class="date-item badge">8</button></div>
            <div class="calendar-date current-month"><button class="date-item">9</button></div>
            <div class="calendar-date current-month"><button class="date-item">10</button></div>
            <div class="calendar-date current-month"><button class="date-item">11</button></div>
            <div class="calendar-date current-month"><button class="date-item">12</button></div>
            <div class="calendar-date current-month"><button class="date-item">13</button></div>
            <div class="calendar-date current-month"><button class="date-item">14</button></div>
            <div class="calendar-date current-month"><button class="date-item">15</button></div>
            <div class="calendar-date current-month calendar-range range-start"><button class="date-item active">16</button></div>
            <div class="calendar-date current-month calendar-range"><button class="date-item">17</button></div>
            <div class="calendar-date current-month calendar-range"><button class="date-item">18</button></div>
            <div class="calendar-date current-month calendar-range"><button class="date-item">19</button></div>
            <div class="calendar-date current-month calendar-range range-end"><button class="date-item active">20</button></div>
            <div class="calendar-date current-month"><button class="date-item">21</button></div>
            <div class="calendar-date current-month"><button class="date-item">22</button></div>
            <div class="calendar-date current-month"><button class="date-item">23</button></div>
            <div class="calendar-date current-month"><button class="date-item">24</button></div>
            <div class="calendar-date current-month"><button class="date-item">25</button></div>
            <div class="calendar-date current-month"><button class="date-item">26</button></div>
            <div class="calendar-date current-month"><button class="date-item">27</button></div>
            <div class="calendar-date current-month"><button class="date-item">28</button></div>
            <div class="calendar-date current-month"><button class="date-item">29</button></div>
            <div class="calendar-date current-month"><button class="date-item">30</button></div>
            <div class="calendar-date current-month"><button class="date-item">31</button></div>
            <div class="calendar-date next-month disabled"><button class="date-item">1</button></div>
        </div>
    </div>
</div>
-->