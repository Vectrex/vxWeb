<table>
	<thead>
		<tr>
			<th class="sortableHeader">Dateiname</th>
			<th class="sm right sortableHeader">Größe</th>
			<th class="sm center sortableHeader">Typ/Vorschau</th>
			<th class="sm right sortableHeader">Erstellt</th>
			<th class="sm center sortableHeader">referenziert</th>
			<th class="ssm"></th>
		</tr>
	</thead>

	<tbody>

	<?php $rowCount = 0; ?>

	<?php if(($parent = $tpl->currentFolder->getParentMetaFolder())): ?>

		<tr class="row<?php echo $rowCount++ % 2;?>">
			<td>
				<a class="folderLink" href="$files?folder=<?php echo $parent->getId(); ?>&amp;force=htmlonly" onclick="gotoFolder(<?php echo $parent->getId(); ?>, event)">
					<img src="$/icons/darkgray/round_and_up_icon&amp;24.png" alt="übergeordneter Ordner" title="übergeordneter Ordner"> &bull;&bull;
				</a>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>

	<?php endif; ?>

	<?php foreach($tpl->currentFolder->getMetafolders() as $f): ?>

		<tr class="folderRow row<?php echo $rowCount++ % 2;?>">
			<td>
				<a class="folderLink" href="$files?folder=<?php echo $f->getId(); ?>&amp;force=htmlonly"><?php echo $f->getName(); ?></a>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>

	<?php endforeach; ?>

	<?php if(empty($tpl->files)): ?>
		<tr><td><em>Ordner ist leer</em></td></tr>
	<?php endif; ?>

	<?php foreach($tpl->files as $f): ?>
		<?php $data = $f->getData(); ?>

		<tr class="fileRow row<?php echo $rowCount++ % 2;?><?php if($tpl->editing == $f->getId()) { echo ' hiLite'; } ?>">
			<td>
				<?php echo $f->getMetaFilename(); ?><?php if($f->getFilename() != $f->getMetaFilename()): ?> (<span style="color: #f80; font-style: italic;"><?php echo $f->getFilename(); ?></span>)<?php endif; ?><br>
				<em class="smaller"><?php echo $data['Title']; ?></em>
			</td>
			<td><?php
				$fs = $f->getFileInfo()->getSize();
					echo $fs > 1000000 ?
						number_format($fs/1048576, 2, DECIMAL_POINT, THOUSANDS_SEP).'MB' :
						number_format($fs/1024, 2, DECIMAL_POINT, THOUSANDS_SEP).'kB'; ?></td>

					<td class="sm">
					<?php if(!preg_match('~^image/(png|gif|jpeg)$~', $f->getMimeType())) : ?>
						<?php echo $f->getMimeType(); ?>
					<?php else: ?>
						<img class="thumb" src="<?php echo htmlspecialchars(str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '',$f->getPath())); ?>#crop 1|resize 0 40" alt="">
					<?php endif; ?>
					</td>

			<td><?php echo date('Y-m-d<b\r>H:i:s', $f->getFileInfo()->getMTime()); ?></td>

			<td><?php echo (string) $f->getReferencedTable(); ?><br><?php echo (string) $f->getReferencedId(); ?></td>

			<td>
				<?php if(isset($tpl->isEmbedded) && $tpl->isEmbedded): ?>

				<?php if($tpl->filter != 'image' || preg_match(Rex::IMAGE_MIMETYPE, $f->getMimeType())): ?>
					<img class="submitImg" src="/img/site/icons/darkgray/doc_export_icon&24.png" alt="Datei übernehmen" title="Datei übernehmen" onclick="selectFile('<?php echo htmlspecialchars(str_replace(rtrim($_SERVER['DOCUMENT_ROOT'], '/'), '',$f->getPath())); ?>')">
				<?php else: ?>
				<img src="/img/site/icons/darkgray/padlock_closed_icon&24.png" alt="Datei kann nicht übernommen werden" title="Datei kann nicht übernommen werden">
				<?php endif; ?>

				<?php else: ?>

				<?php if($tpl->editing != $f->getId()): ?>
				<a href='$files/edit?file=<?php echo $f->getId(); ?>&amp;force=htmlonly'>
					<img src="/img/site/icons/darkgray/doc_edit_icon&amp;24.png" alt="Metadaten ändern" title="Metadaten ändern">
				</a>
				<a href='$files/del?file=<?php echo $f->getId(); ?>&amp;force=htmlonly'>
					<img src="/img/site/icons/darkgray/doc_delete_icon&amp;24.png" alt="Löschen" title="Löschen">
				</a>
				<?php endif; ?>

				<?php endif; ?>
			</td>
		</tr>

	<?php endforeach; ?>

</table>