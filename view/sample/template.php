<!-- { extend: layout.php @ content_block } -->

<h1>Welcome to vxWeb</h1>

<div class="divider" data-content="Display an assigned variable"></div>
<p><?= $this->explanation ?></p>
<div class="divider" data-content="Include another template file"></div>
<p><?= $this->includeFile('sample/include_template.php') ?></p>
<div class="divider" data-content="Include a controller response"></div>
<?= $this->includeControllerResponse('Sample/Form') ?>