<?php $currentUsername = vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername(); ?>

<tr class="<?php if ($currentUsername === $this->user['username']): ?>disabled<?php endif; ?>">

	<td><?= $this->user['username'] ?></td>
	<td><?= $this->user['name'] ?></td>
	<td><?= $this->user['email'] ?></td>
	<td><?= $this->user['alias'] ?></td>
	<td class="right">
		<?php if ($currentUsername != $this->user['username']): ?>
			<a class="btn btn-primary webfont-icon-only" data-icon="&#xe002;" href="$users?id=<?= $this->user['username'] ?>"></a>
			<a class="btn btn-primary webfont-icon-only" data-icon="&#xe011;" href="$users/del?id=<?= $this->user['username'] ?>" onclick="return window.confirm('Wirklich löschen?');" title="Löschen"></a>
		<?php endif; ?>
	</td>

</tr>
