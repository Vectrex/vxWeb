<!-- {extend: admin/layout_with_menu.php @ content_block } -->
<h1>Dateien</h1>

<div id="files">

	<?php if(empty($tpl->form)): ?>
	<div>
		<a id="addButton" class="button addButton" href='$files/add?folder=<?php echo $tpl->folderID; ?>&amp;force=htmlonly'><span>Datei hinzufügen</span></a>
	</div>
	<?php else: ?>
		<?php echo $tpl->form; ?>
	<?php endif; ?>

	<div id="directoryBar">
		<?php foreach($tpl->directoryBar as $button): ?>
			<a href="$files?folder=<?php echo $button->getId(); ?>&amp;force=htmlonly"><?php echo $button->getName(); ?></a>
		<?php endforeach; ?>
	</div>

	<div id="filesList">
		<?php echo $tpl->filesTable; ?>
	</div>

</div>
