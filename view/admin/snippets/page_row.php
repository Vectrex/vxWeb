<tr>
	<td><?php echo $this->page->getAlias(); ?><br><em><?php echo $this->page->getTitle(); ?></em></td>
	<td><?php echo $this->page->getTemplate(); ?></td>
	<td class="shortened_60" style="font-size: 85%;"><?php echo ''; ?></td>
	<td class=""><?php echo $this->page->getActiveRevision()->getFirstCreated()->format('Y-m-d H:i:s'); ?></td>
	<td class=""><?php echo count($this->page->getRevisions()); ?></td>
	<td>
		<a class="btn btn-primary webfont-icon-only" data-icon="&#xe002;" href="$pages?id=<?php echo $this->page->getId(); ?>"></a>
	</td>
</tr>

