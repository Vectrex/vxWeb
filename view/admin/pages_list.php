<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>Seiten</h1>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.origin + "/admin/pages__sort__",
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

	<?php $rowNdx = 0; ?>

	<?php foreach($this->pages as $this->page): ?>
		<?php $this->colorNdx = $rowNdx++ % 2; ?>
		<?php $this->includeFile('admin/snippets/page_row.php'); ?>
	<?php endforeach; ?>

</table>
