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
			skinClass: 'calendarSkin',
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
	vxJS.widget.calendar(
		document.getElementsByName("display_from")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[1],
			noYearInput: true,
			noPast: true,
			skinClass: 'calendarSkin',
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
	vxJS.widget.calendar(
		document.getElementsByName("display_until")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[2],
			noYearInput: true,
			noPast: true,
			skinClass: 'calendarSkin',
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

        <span class="btn-group" id="directoryBar"></span>

        <div id="filesList">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="vx-sortable-header">Dateiname</th>
                        <th class="vx-sortable-header">Größe</th>
                        <th class="vx-sortable-header">Typ/Vorschau</th>
                        <th class="vx-sortable-header">Erstellt</th>
                        <th class="">Link</th>
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
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

