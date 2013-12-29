<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<h1>Artikel &amp; News</h1>

<a class="button addButton" href="$articles/new">Artikel anlegen</a>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.href + "__sort__",
			t = vxJS.widget.sorTable(
			vxJS.dom.getElementsByClassName("list")[0],	{
				columnFormat: [
					null,
					null,
					"date_iso",
					"date_iso",
					"date_iso",
					"float",
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

<table class="list">
	<tr>
		<th class="m">Kategorie</th>
		<th class="l">Titel</th>
		<th class="right">Artikeldatum</th>
		<th class="right">Anzeige von</th>
		<th class="right">Anzeige bis</th>
		<th class="centered">Sortierziffer</th>
		<th class="right">Angelegt/aktualisiert</th>
		<th>&nbsp;</th>
	</tr>
<?php if(!empty($tpl->articles)): ?>
	<?php $color = 0; ?>
	<?php foreach($tpl->articles as $article): ?>
		<tr class="row<?php echo $color++ % 2; ?>">
			<td><?php echo $article->getCategory()->getTitle(); ?></td>
			<td><?php echo $article->getHeadline(); ?></td>
			<td class="right"><?php echo is_null($article->getDate()) ? '' : $article->getDate()->format('Y-m-d'); ?></td>
			<td class="right"><?php echo is_null($article->getDisplayFrom()) ? '' : $article->getDisplayFrom()->format('Y-m-d'); ?></td>
			<td class="right"><?php echo is_null($article->getDisplayUntil()) ? '' : $article->getDisplayUntil()->format('Y-m-d'); ?></td>
			<td class="centered"><?php echo $article->getCustomSort(); ?></td>
			<td class="right"><?php echo is_null($article->getLastUpdated()) ? '' : $article->getLastUpdated()->format('Y-m-d H:i:s'); ?></td>
			<td>
				<a class="iconButton editButton" href="$articles?id=<?php echo $article->getId(); ?>" title="Bearbeiten"></a>
				<a class="iconButton deleteButton" href="$articles/del?id=<?php echo $article->getId(); ?>" onclick="return window.confirm('Wirklich löschen?');" title="Löschen"></a>
			</td>
		</tr>
	<?php endforeach; ?>

<?php else: ?>
	<tr><td colspan="8">Keine angelegt.</td></tr>
<?php endif;?>

</table>