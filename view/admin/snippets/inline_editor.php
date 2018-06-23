<?php

// we need the admin routes
$router = new \vxPHP\Routing\Router(\vxPHP\Application\Application::getInstance()->getConfig()->routes['admin.php']);

?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" type="text/css" href="/css/inline.min.css">

<script>

    "use strict";

    if(!this.vxWeb) {
        this.vxWeb = {};
    }
    if(!this.vxWeb.routes) {
        this.vxWeb.routes = {};
    }

    this.vxWeb.routes.filePicker = "<?= $router->getRoute('filepicker')->getUrl() ?>";
    this.vxWeb.routes.inlineUpdate = "<?= $router->getRoute('inlineEditXhr')->getUrl() ?>";

    CKEDITOR.disableAutoInline = true;

    document.addEventListener("DOMContentLoaded", function() {

        var element = document.querySelector('*[contenteditable="true"]'),
            page = "<?= $this->page->getAlias() ?>",
            inlineEditor = CKEDITOR.inline(element, {
                toolbar: [
                    { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                    { name: 'editing', items: [ 'Find', 'Replace'] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Blockquote'] },
                    { name: 'links', items: [ 'Link', 'Unlink'] },
                    { name: 'insert', items: [ 'Image', 'Table', 'SpecialChar'] },
                    { name: 'styles', items: [ 'Styles', 'Format' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'ShowBlocks' ] }
                ],

                customConfig: "",
                filebrowserBrowseUrl: vxWeb.routes.filePicker,
                filebrowserImageBrowseUrl: vxWeb.routes.filePicker + "?filter=image"
            }),
            lastData;

        var messageToast = function(selector) {

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

        inlineEditor.on("focus", function() {
            element.classList.add("editing");
            lastData = this.getData();
        });

        inlineEditor.on("blur", function() {
            element.classList.remove("editing");

            if(lastData === this.getData()) {
                return;
            }

            // save

            fetch(vxWeb.routes.inlineUpdate, {
                body: JSON.stringify({ data: inlineEditor.getData(), page: page }),
                credentials: "same-origin",
                headers: {
                    "content-type": "application/json"
                },
                method: 'POST'
            })
            .then(function(response) {

                var contentType = response.headers.get("content-type");

                if(response.ok) {

                    if (contentType && contentType.includes("application/json")) {
                        return response.json();
                    }

                    throw new Error("Response failed (" + response.status + " - " + response.statusText + ").");

                }
            })
            .then(function(response) {
                messageToast().show(response.message, "toast-success");
            })
            .catch(function(error) {
                messageToast().show(error.message || "Server Error.", "toast-error");
            });

        });
    
    });
</script>
