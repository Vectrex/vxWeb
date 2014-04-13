<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/admin/doArticles.js"></script>
<script type="text/javascript" src="/js/admin/doFiles.js"></script>

<script type="text/javascript">
	if(!this.vxWeb.parameters) {
		this.vxWeb.parameters = {};
	}
	
	this.vxWeb.routes.articles = "<?php echo vxPHP\Http\Router::getRoute('articlesXhr', 'admin.php')->getUrl(); ?>?<?php echo vxPHP\Http\Request::createFromGlobals()->getQueryString(); ?>";
	this.vxWeb.routes.files = "<?php echo vxPHP\Http\Router::getRoute('fileincludeXhr', 'admin.php')->getUrl(); ?>";

	this.vxWeb.parameters.fileColumns = ["name", "size", "mime", "mTime", "linked"];
	this.vxWeb.parameters.articlesId = <?php echo vxPHP\Http\Request::createFromGlobals()->query->get('id', 'null'); ?>;
	
	vxJS.event.addDomReadyListener(this.vxWeb.doArticles);
	vxJS.event.addDomReadyListener(this.vxWeb.doFiles);
</script>

<script type="text/javascript">
vxJS.event.addDomReadyListener(function() {
	CKEDITOR.replace(document.forms[0].elements['Content'], {
		extraAllowedContent: "div(*)",
		toolbar:
			[
			    ['Maximize','-','Source', '-', 'Undo','Redo'],
			    ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord','-','RemoveFormat'],
			    ['Bold', 'Italic', '-', 'NumberedList','BulletedList', '-', 'Table', '-', 'Link', 'Unlink', 'Anchor']
			], height: "20em", width: "52em", contentsCss: '/css/default.css'
		} );

	vxJS.widget.calendar(
		document.getElementsByName("Article_Date")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[0],
			noYearInput: true,
			skinClass: 'calendarSkin',
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
	vxJS.widget.calendar(
		document.getElementsByName("Display_from")[0],
		{	trigger: vxJS.dom.getElementsByClassName("calendarPopper")[1],
			noYearInput: true,
			noPast: true,
			skinClass: 'calendarSkin',
			inputLocale: "date_de",
			outputFormat: "%D.%M.%Y"
		}
	);
	vxJS.widget.calendar(
		document.getElementsByName("Display_until")[0],
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

<h1>Artikel &amp; News</h1>

<a class="button prevButton" href="$<?php echo $tpl->backlink; ?>">Zurück zur Übersicht</a>

<div class="vxJS_tabThis">

	<div class="vxJS_tabSpacer"></div>

	<div class="section">
		<h2 id="article_content">Inhalt</h2>
		<?php echo $tpl->article_form; ?>
	</div>

	<div class="section">
		<h2 id="article_files">Dateien</h2>
		<?php echo $tpl->files_form; ?>
		
		<div id="files">

	<div id="directoryBar"></div>

	<div id="filesList">
		<table>
			<thead>
				<tr>
					<th class="sortableHeader">Dateiname</th>
					<th class="sm right sortableHeader">Größe</th>
					<th class="sm center sortableHeader">Typ/Vorschau</th>
					<th class="sm right sortableHeader">Erstellt</th>
					<th class="xs center">Link</th>
					<th class="sm"></th>
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
</div>