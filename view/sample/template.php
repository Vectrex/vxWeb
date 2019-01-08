<?php /* @var \vxPHP\Template\TemplateBuffer $this */ ?>

<!-- { extend: layout.php @ content_block } -->

<h1>Welcome to vxWeb</h1>

<div class="divider" data-content="Display an assigned variable"></div>
<p><?= $this->explanation ?></p>
<div class="divider" data-content="Include another template file"></div>
<div class="empty bg-secondary"><p><?= $this->includeFile('sample/include_template.php') ?></p></div>
<div class="divider" data-content="Include a controller response"></div>
<?= $this->includeControllerResponse('Sample/Form', 'includeForm') ?>