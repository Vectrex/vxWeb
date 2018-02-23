<tr>
	<td><?php echo $this->page->getAlias(); ?><br><em><?php echo $this->page->getTitle(); ?></em></td>
	<td><?php echo $this->page->getTemplate(); ?></td>
	<td class="shortened_60" style="font-size: 85%;"><?php echo ''; ?></td>
	<td><?php echo $this->page->getActiveRevision()->getFirstCreated()->format('Y-m-d H:i:s'); ?></td>
	<td><?php echo count($this->page->getRevisions()); ?></td>
	<td>
		<a class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Bearbeiten" href="$pages?id=<?php echo $this->page->getId(); ?>">&#xe002;</a>
	</td>
</tr>

