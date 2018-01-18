<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<h1>Artikel &amp; News</h1>

<div class="vx-button-bar">
	<a class="btn with-webfont-icon-right" data-icon="&#xe018;" href="$articles/new">Artikel anlegen</a>
</div>

<script type="text/javascript">

	"use strict";
	
	this.vxWeb.routes.publish = "<?= vxPHP\Routing\Router::getRoute('publishXhr', 'admin.php')->getUrl() ?>";
	this.vxWeb.routes.filter = "<?= vxPHP\Routing\Router::getRoute('articles', 'admin.php')->getUrl(['action' => 'list']) ?>";

	/**
	 * executes XHR with filter parameters and returns filtered data
	 */
	this.vxWeb.tableFilter = function(table, filterXhr) {

		var timeoutId,
			inputs = vxJS.collectionToArray(table.tHead.getElementsByTagName("input")), i = inputs.length,
			selects = vxJS.collectionToArray(table.tHead.getElementsByTagName("select")), s = inputs.length,
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

		var handleChange = function() {
			filter[this.name.split("_")[1]] = this.value;
			applyFilter();
		};

		var setRows = function() {
			var r = this.response;

			totalCount		= r.totalCount;
			filteredCount	= r.filteredCount;
			filteredRows	= r.rows;

			vxJS.event.serve(that, "filterApplied");
		};

		while(i--) {
			vxJS.event.addListener(inputs[i], "keyup", handleKeyUp);
			filter[inputs[i].name.split("_")[1]] = inputs[i].value.trim();
		}
		while(s--) {
			vxJS.event.addListener(selects[s], "change", handleChange);
			filter[selects[s].name.split("_")[1]] = selects[s].value;
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
		var lsValue, lsKey = window.location.origin + "/admin/articles__sort__",
			t = vxJS.widget.sorTable(
                document.querySelector(".table"),	{
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


		var filterXhr = vxJS.xhr(
			{ uri: vxWeb.routes.filter }
		);

		var filteredTable = vxWeb.tableFilter(
			document.querySelector(".table"),
			filterXhr
		);

		vxJS.event.addListener(
			t,
			"finishSort",
			function() {
				var c = this.getActiveColumn(), columnName = t.element.parentNode.firstElementChild.rows[0].cells[c.ndx].getAttribute("data-column-name");
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc, columnName: columnName } ));
				filterXhr.use(null,  { sortByColumn: columnName });
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

		vxJS.event.addListener(filteredTable, "filterApplied", function() {
			var rows, row, tmpTable;

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
				filterXhr.use(null,  { sortByColumn: lsValue.columnName });
				t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
			}
		}

		filteredTable.applyFilter();

	});
</script>

<table class="table table-striped">
	<thead>
		<tr>
			<th data-column-name="category">
                <select name="filter_category" class="form-select">
                    <option value="">(kein Filter)</option>
                    <?php foreach($this->categories as $cat): ?>
                        <option value="<?= $cat->getId() ?>"><?= $cat->getTitle() ?></option>
                    <?php endforeach; ?>
                </select>
            </th>
			<th data-column-name="headline">
                <input class="form-input" name="filter_title" placeholder="Titel filtern...">
            </th>
			<th data-column-name="published"></th>
			<th data-column-name="article_date"></th>
			<th data-column-name="display_from"></th>
			<th data-column-name="display_until"></th>
			<th data-column-name="customsort"></th>
			<th data-column-name="lastupdated"></th>
			<th></th>
		</tr>
		<tr>
			<th class="m">Kategorie</th>
			<th>Titel</th>
			<th class="">Pub</th>
			<th class="">Artikeldatum</th>
			<th class="">Anzeige von</th>
			<th class="">Anzeige bis</th>
			<th class="">Sortierziffer</th>
			<th class="">Angelegt/aktualisiert</th>
			<th class="">&nbsp;</th>
		</tr>
	</thead>
</table>