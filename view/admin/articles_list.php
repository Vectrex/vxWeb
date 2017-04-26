<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<h1>Artikel &amp; News</h1>

<div class="buttonBar">
	<a class="buttonLink withIcon" data-icon="&#xe018;" href="$articles/new">Artikel anlegen</a>
</div>

<script type="text/javascript">

	"use strict";
	
	this.vxWeb.routes.publish = "<?= vxPHP\Routing\Router::getRoute('publishXhr', 'admin.php')->getUrl() ?>";
	this.vxWeb.routes.filter = "<?= vxPHP\Routing\Router::getRoute('articles', 'admin.php')->getUrl(['action' => 'filter']) ?>";

	/**
	 * executes XHR with filter parameters and returns filtered data
	 */
	this.vxWeb.tableFilter = function(table, filterXhr) {

		var timeoutId,
			inputs = vxJS.collectionToArray(table.tHead.getElementsByTagName("input")), l = inputs.length,
			filter = {},
			that = {},

			filteredRows,
			totalCount,
			filteredCount;

		var applyFilter = function() {
			filterXhr.use(null, vxJS.merge(filterXhr.getParameters(), { filter: filter }) ).submit();
		};

		var handleKeyUp = function() {
			var val = this.value.trim(), col = this.name.split("_")[1];

			window.clearTimeout(timeoutId);

			if(filter[col] !== val) {
				filter[col] = val;
				timeoutId = window.setTimeout(applyFilter, 350);
			}
		};

		var setRows = function() {
			var r = this.response;

			totalCount		= r.totalCount;
			filteredCount	= r.filteredCount;
			filteredRows	= r.rows;

			vxJS.event.serve(that, "filterApplied");
		};

		while(l--) {

			vxJS.event.addListener(inputs[l], "keyup", handleKeyUp);

			filter[inputs[l].name.split("_")[1]] = inputs[l].value.trim();
		}

		vxJS.event.addListener(filterXhr, "complete", setRows);

		that.getFilteredCount = function() {
			return filteredCount;
		};
		
		that.getTotalCount = function() {
			return totalCount;
		};

		that.getFilteredRows = function() {
			return filteredRows;
		};

		that.clearFilter = function() {
			var l = inputs.length;

			while(l--) {
				inputs[l].value = "";
			}

			filter = {};
		};

		that.applyFilter = applyFilter;

		that.element = table;

		return that;

	};

	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.href + "__sort__",
			t = vxJS.widget.sorTable(
			vxJS.dom.getElementsByClassName("list")[0],	{
				columnFormat: [
					null,
					null,

					// checkbox sort

					function(a, b) {

						var checked1 = a.element.cells[this.ndx].getElementsByTagName("input")[0].checked,
							checked2 = b.element.cells[this.ndx].getElementsByTagName("input")[0].checked;

						if(checked1 === checked2) {
							return 0;
						}
						if(this.asc) {
							return checked2 ? -1 : 1;
						}
						else {
							return checked1 ? -1 : 1;
						}
					},
					"date_iso",
					"date_iso",
					"date_iso",
					"float",
					null,
					"no_sort"
				]
			}),
			publishXhr = vxJS.xhr( { uri: vxWeb.routes.publish } );

		vxJS.event.addListener(
			t,
			"finishSort",
			function() {
				var c = this.getActiveColumn();
				vxJS.widget.shared.shadeTableRows({ element: this.element });
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc } ));
			}
		);

		vxJS.event.addListener(t, "click", function() {
			var matches;

			if(this.type === "checkbox") {
				if(matches = this.name.match(/^publish\[(\d+)\]$/)) {
					publishXhr.use(null, { id: matches[1], state: this.checked ? 1 : 0 }).submit();
					if(t.getActiveColumn().ndx === 2) {
						t.reSort();
					}
				}
			}

		});

		var filteredTable = vxWeb.tableFilter(
			vxJS.dom.getElementsByClassName("list")[0],
			vxJS.xhr(
				{ uri: vxWeb.routes.filter },
				null
			)
		);
		
		vxJS.event.addListener(filteredTable, "filterApplied", function() {
			var i, l, rows, row, tmpTable;

			t.removeAllRows();

			if((rows = this.getFilteredRows()) && rows.length) {

				tmpTable = "table".create();
				tmpTable.innerHTML = rows.join("");
		
				while(row = tmpTable.tBodies[0].rows[0]) {
					t.insertRow(row);
				}
		
				t.reSort();
				
			}
		});

		if(window.localStorage) {
			if((lsValue = window.localStorage.getItem(lsKey))) {
				lsValue = JSON.parse(lsValue);
				t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
			}
		}
	});
</script>

<table class="list pct_100">
	<thead>
		<tr>
			<th><input class="pct_100" name="filter_category" placeholder="Kategorie filtern..."></th>
			<th><input class="pct_100" name="filter_title" placeholder="Titel filtern..."></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		<tr>
			<th class="m">Kategorie</th>
			<th>Titel</th>
			<th class="xss center">Pub</th>
			<th class="m right">Artikeldatum</th>
			<th class="m right">Anzeige von</th>
			<th class="m right">Anzeige bis</th>
			<th class="sm centered">Sortierziffer</th>
			<th class="ml right">Angelegt/aktualisiert</th>
			<th class="ssm">&nbsp;</th>
		</tr>
	</thead>
<?php if(!empty($tpl->articles)): ?>

	<?php $this->color = 0; ?>

	<?php foreach($tpl->articles as $this->article): ?>
	
		<?php $this->color = $this->color++ % 2; ?>
		<?php $this->includeFile('admin/snippets/article_row.php'); ?>
	
	<?php endforeach; ?>

<?php else: ?>
	<tr><td colspan="8">Keine angelegt.</td></tr>
<?php endif;?>

</table>