<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>Seiten</h1>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.href + "__sort__",
			t = vxJS.widget.sorTable(
			vxJS.dom.getElementsByClassName("list")[0],	{
				columnFormat: [
					null,
					null,
					"no_sort",
					"date_iso",
					null,
					"no_sort"
				]
			});

		vxJS.event.addListener(
			t,
			"finishSort",
			function() {
				var c = this.getActiveColumn();
				vxJS.widget.shared.shadeTableRows({ element: this.element });
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc } ));
			}
		);

		if(window.localStorage) {
			if((lsValue = window.localStorage.getItem(lsKey))) {
				lsValue = JSON.parse(lsValue);
				t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
			}
			else {
				vxJS.widget.shared.shadeTableRows({ element: t.element });
			}
		}

		else {
			vxJS.widget.shared.shadeTableRows({ element: t.element });
		}
		
	});
</script>

<table class="list pct_100">
	<tr>
		<th class="mml">Alias/Titel</th>
		<th class="m">Template</th>
		<th>Inhalt</th>
		<th class="right mml">letzte Änderung</th>
		<th class="right xs">#Rev</th>
		<th class="s">&nbsp;</th>
	</tr>

	<?php foreach($tpl->pages as $p): ?>
	<tr>

		<td><?php echo $p->getAlias(); ?><br><em><?php echo $p->getTitle(); ?></em></td>
		<td><?php echo $p->getTemplate(); ?></td>
		<td class="shortened_60" style="font-size: 85%;"><?php echo ''; ?></td>
		<td class="right"><?php echo $p->getActiveRevision()->getFirstCreated()->format('Y-m-d H:i:s'); ?></td>
		<td class="right"><?php echo count($p->getRevisions()); ?></td>
		<td>
			<a class="buttonLink iconOnly" data-icon="&#xe002;" href="$pages?id=<?php echo $p->getId(); ?>"></a>
		</td>
	</tr>

	<?php endforeach; ?>

</table>
