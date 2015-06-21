<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<?php if($tpl->allow_nice_edit): ?>

	<?php $url = vxPHP\Routing\Router::getRoute('filepicker', 'admin.php')->getUrl(); ?>

	<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

	<script type="text/javascript">

		"use strict";

		this.vxWeb.routes.page	= "<?php echo vxPHP\Routing\Router::getRoute('pagesXhr', 'admin.php')->getUrl(); ?>?<?php echo vxPHP\Http\Request::createFromGlobals()->getQueryString(); ?>";

		vxJS.event.addDomReadyListener(function() {
	
			CKEDITOR.replace(document.forms[0].elements["Markup"], {
				allowedContent: true,
				autoParagraph: false,
				// extraAllowedContent: "div(*)",
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
					height: "450px", contentsCss: ['/css/vxweb.css', '/css/default.css'],
					filebrowserBrowseUrl: "<?php echo $url; ?>",
					filebrowserImageBrowseUrl: "<?php echo $url; ?>?filter=image"
				}
			);

			var revisionsContainer = document.getElementById("revisions").tBodies[0],
				form = document.forms[0],
				needsFormData = true;

			var fillRevisions = function(data) {

				var revs, rev, i, l;

				vxJS.dom.deleteChildNodes(revisionsContainer);

				if(revs = data.revisions) {

					revs.sort(function(a, b) {return a.firstCreated < b.firstCreated ? 1 : -1; });

					for(i = 0, l = revs.length; i < l; ++i) {
						rev = revs[i];
						revisionsContainer.appendChild(
							"tr".setProp("id", "rev" + rev.id).create([
								"td".create(rev.locale || "-"),
								"td".create(
									"a".setProp("href", "#rev" + rev.id).create((new Date(rev.firstCreated)).format("%Y-%M-%D %H:%I:%S"))
								),
								"td".create(
									"input".setProp( { value: "rev" + rev.id, type: "checkbox", checked: rev.active } ).create()
								)
							])
						);
					}
				}

				if(needsFormData) {
					rev = revs[0];
					for(i = 0; i < l; ++i) {
						if(revs[i].active) {
							rev = revs[i];
							break;
						}
					}
					revisionDataXhr.use(null, { id: rev.id } ).submit();
				}
			};

			var setRevisionData = function(data) {

				var previous;

				needsFormData = false;

				if(data.markup) {
					CKEDITOR.instances.Markup.setData(
						data.markup,
						{
							callback: function() {
								this.checkDirty();
								this.resetUndo();
							}
						}
					);

					form["Title"].value			= data.title;
					form["Keywords"].value		= data.keywords;
					form["Description"].value	= data.description;

					if(previous = revisionsContainer.querySelector("tr.active")) {
						vxJS.dom.removeClassName(previous, "active");
					}
					
					vxJS.dom.addClassName(revisionsContainer.querySelector("tr#rev" + data.id), "active");
				}	
			};

			var activationXhr = vxJS.xhr(
				{
					uri: vxWeb.routes.page,
					command: "changeActivationOfRevision"
				},
				null,
				null,
				{
					complete: function() {
					}
				}
				
			);
			var revisionsXhr = vxJS.xhr(
				{
					uri: vxWeb.routes.page,
					command: "getRevisions"
				},
				null,
				null,
				{
					complete: function() { fillRevisions(this.response); }
				}
			);

			var revisionDataXhr = vxJS.xhr(
				{
					uri: vxWeb.routes.page,
					command: "getRevisionData"
				},
				null,
				null,
				{
					complete: function() { setRevisionData(this.response); }
				}
			);

			var handleRevisionsClick = function(e) {

				var id, checked;

				switch(this.nodeName.toLowerCase()) {
					case "a":
						if(!needsFormData) {
							vxJS.event.cancelBubbling(e);
							vxJS.event.preventDefault(e);
							id = this.hash.match(/#rev([1-9][0-9]*)/)[1];
							revisionDataXhr.use(null, { id: id } ).submit();
						}
						break;
					
					case "input":
						id		= this.value.match(/rev([1-9][0-9]*)/)[1];
						checked	= this.checked;
						vxJS.collectionToArray(revisionsContainer.querySelectorAll("input[type='checkbox']")).forEach(function(elem) {
							elem.checked = false;
						});
						this.checked = checked;
						activationXhr.use(null, { id: id, activate: checked } ).submit();
						break;
				}
			};

			revisionsXhr.submit();
			vxJS.event.addListener(revisionsContainer, "click", handleRevisionsClick);
		});
	</script>

<?php endif; ?>

<h1>Seiten</h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe025;" href="$pages">Zurück zur Übersicht</a>
</div>

<?php echo $tpl->form; ?>
