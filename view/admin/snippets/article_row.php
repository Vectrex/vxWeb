<tr>
	<td><?= $this->article->getCategory()->getTitle() ?></td>
	<td><?= $this->article->getHeadline() ?></td>
	<td class="">
        <label class="form-switch">
            <input type="checkbox" name="publish[<?= $this->article->getId(); ?>]"
                   <?php if ($this->article->isPublished()): ?>checked="checked"<?php endif; ?>
                <?php if(!$this->can_publish): ?> disabled="disabled"<?php endif; ?>
            >
            <i class="form-icon"></i>
        </label>
	</td>
	<td class=""><?= is_null($this->article->getDate()) ? '' : $this->article->getDate()->format('Y-m-d') ?></td>
	<td class=""><?= is_null($this->article->getDisplayFrom()) ? '' : $this->article->getDisplayFrom()->format('Y-m-d') ?></td>
	<td class=""><?= is_null($this->article->getDisplayUntil()) ? '' : $this->article->getDisplayUntil()->format('Y-m-d') ?></td>
	<td class=""><?= $this->article->getCustomSort() ?></td>
	<td class=""><?= is_null($this->article->getLastUpdated()) ? '' : $this->article->getLastUpdated()->format('Y-m-d H:i:s') ?></td>
	<td>
		<a class="btn btn-primary webfont-icon-only" data-icon="&#xe002;" href="$articles?id=<?= $this->article->getId() ?>" title="Bearbeiten"></a>
		<a class="btn btn-primary webfont-icon-only" data-icon="&#xe011;" href="$articles/del?id=<?= $this->article->getId() ?>" onclick="return window.confirm('Wirklich löschen?');" title="Löschen"></a>
	</td>
</tr>
