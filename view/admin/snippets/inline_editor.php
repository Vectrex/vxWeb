<?php

// we need the admin routes
$router = new \vxPHP\Routing\Router(\vxPHP\Application\Application::getInstance()->getConfig()->routes['admin.php']);

?>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.disableAutoInline = true;
    document.addEventListener("DOMContentLoaded", function() {

        var element = document.querySelector('*[contenteditable="true"'),
            inlineEditor = CKEDITOR.inline(element, {}),
            page = "<?= $this->page->getAlias() ?>";

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
            .then(response => response.json());

        });
    
    });
</script>
