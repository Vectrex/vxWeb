<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>Seiten</h1>

<script type="text/javascript">
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.origin + "/admin/pages__sort__",
			t = vxJS.widget.sorTable(
                document.querySelector(".table"),	{
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

<table class="table table-striped">
	<tr>
		<th class="col-2 vx-sortable-header">Alias/Titel</th>
		<th class="vx-sortable-header">Template</th>
		<th>Inhalt</th>
		<th class="col-2 vx-sortable-header">letzte Änderung</th>
		<th class="col-1 vx-sortable-header">#Rev</th>
		<th class="col-1">&nbsp;</th>
	</tr>

	<?php foreach($this->pages as $this->page): ?>
		<?php $this->includeFile('admin/snippets/page_row.php'); ?>
	<?php endforeach; ?>

</table>
