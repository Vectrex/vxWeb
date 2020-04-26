<?php

// we need the admin routes
$router = new \vxPHP\Routing\Router(\vxPHP\Application\Application::getInstance()->getConfig()->routes['admin.php']);
$filePickerUrl = $router->getRoute('filepicker')->getUrl();
$updateUrl =  $router->getRoute('inline_edit')->getUrl();

?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" type="text/css" href="/css/inline.min.css">

<script>
    CKEDITOR.disableAutoInline = true;

    document.addEventListener("DOMContentLoaded", function() {

        var element = document.querySelector('*[contenteditable="true"]'),
            page = "<?= $this->page->getAlias() ?>",
            inlineEditor = CKEDITOR.inline(element, {
                toolbar: [
                    { name: 'clipboard', items: ['Undo', 'Redo' , '-', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord' ] },
                    { name: 'editing', items: [ 'Find', 'Replace'] },
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Blockquote'] },
                    { name: 'links', items: [ 'Link', 'Unlink'] },
                    { name: 'insert', items: [ 'Image', 'Table', 'SpecialChar'] },
                    { name: 'styles', items: [ 'Styles', 'Format' ] },
                    { name: 'tools', items: [ 'ShowBlocks' ] }
                ],

                customConfig: "",
                filebrowserBrowseUrl: "<?= $filePickerUrl ?>",
                filebrowserImageBrowseUrl: "<?= $filePickerUrl ?>?filter=image"
            }),
            lastData;

        var messageToast = (function(selector) {

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

        }());

        // a request timeout for the fetch API @see https://github.com/github/fetch/issues/175

        var timeout = function(ms, promise) {

            return new Promise(function(resolve, reject) {

                setTimeout(function() {
                    reject(new Error("Request timed out."))
                }, ms);

                promise.then(resolve, reject);

            });
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

            timeout(
                5000,
                fetch("<?= $updateUrl ?>", {
                    body: JSON.stringify({ data: inlineEditor.getData(), page: page }),
                    credentials: "same-origin",
                    headers: {
                        "content-type": "application/json"
                    },
                    method: 'POST'
                })
            )
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
                messageToast.show(response.message, "toast-success");
            })
            .catch(function(error) {
                messageToast.show(error.message || "Server Error.", "toast-error");
            });

        });
    
    });
</script>
