<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script type="text/javascript">
    if(!this.vxWeb) {
        this.vxWeb = {};
    }
</script>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/js/admin/doPages.js"></script>

<script type="text/javascript">
    if(!this.vxWeb.routes) {
        this.vxWeb.routes = {};
    }

    vxWeb.messageToast = function(selector) {

        var mBox, lastAddedClass, timeoutId, button;

        var hide = function() {
            if(mBox) {
                mBox.classList.remove("display");
            }
        };

        var show = function(msg, className) {

            if(mBox === undefined) {
                mBox = document.querySelector(selector || "#messageBox");

                if(mBox && (button = mBox.querySelector("button"))) {
                    button.addEventListener("click", hide);
                }
            }

            if(mBox) {
                if(lastAddedClass) {
                    mBox.classList.remove(lastAddedClass);
                }
                if(className) {
                    mBox.classList.add(className);
                }
                lastAddedClass = className;
            }

            mBox.innerHTML = msg;
            mBox.appendChild(button);
            mBox.classList.add("display");

            if(timeoutId) {
                window.clearTimeout(timeoutId);
            }
            timeoutId = window.setTimeout(hide, 5000);

        };

        return {
            show: show,
            hide: hide
        };
    };

	this.vxWeb.routes.page			= "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('pagesXhr')->getUrl() ?>?<?= vxPHP\Http\Request::createFromGlobals()->getQueryString() ?>";
	this.vxWeb.routes.filePicker	= "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('filepicker')->getUrl() ?>";

</script>

<?php if($tpl->allow_nice_edit): ?>

	<script type="text/javascript">

		vxJS.event.addDomReadyListener(function() {
	
			CKEDITOR.replace(document.forms[0].elements["markup"], {
				allowedContent: true,
				autoParagraph: false,
				customConfig: "",
				toolbar:
					[
					    ['Maximize', '-', 'Source'],
					    ['Undo', 'Redo', '-', 'Cut','Copy','Paste','PasteText','PasteFromWord'],
                        [ 'Find', 'Replace'],
                        [ 'Bold', 'Italic', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat', '-', 'TextColor', 'BGColor'],
                        ['NumberedList','BulletedList','-','Blockquote'],
					    ['Link','Unlink'],
					    ['Image','Table','SpecialChar'],
					    ['Styles', 'Format'],
                        ['ShowBlocks']
					],
					height: "24rem", contentsCss: ['/css/site.css', '/css/site_edit.css'],
					filebrowserBrowseUrl: vxWeb.routes.filePicker,
					filebrowserImageBrowseUrl: vxWeb.routes.filePicker + "?filter=image"
				}

			);

		});

	</script>

<?php endif; ?>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		vxWeb.doPages();
	});
</script>

<h1>Seiten</h1>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="$pages">Zurück zur Übersicht</a>
</div>

<?php echo $tpl->form; ?>
