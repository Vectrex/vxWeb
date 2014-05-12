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

	<?php $color = 0; ?>

	<?php foreach($tpl->pages as $p): ?>
	<tr class="row<?php echo $color++ % 2; ?>">

		<td><?php echo $p['Alias']; ?><br><em><?php echo $p['Title']; ?></em></td>
		<td><?php echo $p['Template']; ?></td>
		<td class="shortened_60" style="font-size: 85%;"><?php echo $p['Rawtext']; ?></td>
		<td class="right"><?php echo $p['LastRevision']; ?></td>
		<td class="right"><?php echo $p['RevCount']; ?></td>
		<td>
			<a class="buttonLink iconOnly" data-icon="&#xe002;" href="$pages?id=<?php echo $p['revisionsID']; ?>"></a>
		</td>
	</tr>

	<?php endforeach; ?>

</table>
