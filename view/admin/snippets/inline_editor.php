<?php

// we need the admin routes
$router = new \vxPHP\Routing\Router(\vxPHP\Application\Application::getInstance()->getConfig()->routes['admin.php']);

?>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" type="text/css" href="/css/inline.min.css">

<script>
    CKEDITOR.disableAutoInline = true;
    document.addEventListener("DOMContentLoaded", function() {

        var element = document.querySelector('*[contenteditable="true"]'),
            inlineEditor = CKEDITOR.inline(element, {}),
            page = "<?= $this->page->getAlias() ?>";

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
        });

        inlineEditor.on("blur", function() {
            element.classList.remove("editing");

            // save

            fetch("<?= $router->getRoute('inlineEditXhr')->getUrl() ?>", {
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
