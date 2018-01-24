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
	<td><?= is_null($this->article->getDate()) ? '' : $this->article->getDate()->format('Y-m-d') ?></td>
	<td><?= is_null($this->article->getDisplayFrom()) ? '' : $this->article->getDisplayFrom()->format('Y-m-d') ?></td>
	<td><?= is_null($this->article->getDisplayUntil()) ? '' : $this->article->getDisplayUntil()->format('Y-m-d') ?></td>
	<td><?= $this->article->getCustomSort() ?></td>
	<td><?= is_null($this->article->getLastUpdated()) ? '' : $this->article->getLastUpdated()->format('Y-m-d H:i:s') ?></td>
	<td>
		<a class="btn btn-primary webfont-icon-only tooltip" data-tooltip="Bearbeiten" href="$articles?id=<?= $this->article->getId() ?>">&#xe002;</a>
		<a class="btn btn-primary webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" href="$articles/del?id=<?= $this->article->getId() ?>" onclick="return window.confirm('Wirklich löschen?');">&#xe011;</a>
	</td>
</tr>
