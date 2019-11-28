<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<h1>Artikel &amp; News</h1>

<div class="vx-button-bar">
	<a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$articles/new">Artikel anlegen</a>
</div>
<!--
<div id="app">
    <table class="table table-striped">
        <thead>
            <tr>
                <th
                        v-for="column in columns"
                        :class="[ 'vx-sortable-header', setHeaderClass(column), columnProperties[column].width ? columnProperties[column].width : '' ]"
                        @click="clickSort(column)"
                >
                    {{ columnProperties[column].label }}
                </th>
                <th class="col-1"></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="article in filteredArticles">
                <td v-for="column in columns" :class="{ 'active': sort.column === column }">{{ article[column] }}</td>
                <td class="right">
                    <a class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'/admin/articles?id=' + article.id">&#xe002;</a>
                    <a class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" :href="'/admin/articles/del?id=' + article.id" onclick="return window.confirm('Wirklich löschen?');">&#xe011;</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
-->
<script type="text/javascript">

	"use strict";

    if(!this.vxWeb) {
        this.vxWeb = {};
    }
    if(!this.vxWeb.routes) {
        this.vxWeb.routes = {};
    }

    vxWeb.messageToast = function(selector) {

        var mBox, lastAddedClass, timeoutId, button;

        var hide = function() {
            if(mBox) {
                mBox.classList.remove("display");
            }
        };

        var show = function(msg, className) {

            if(mBox === undefined) {
                mBox = document.querySelector(selector || "#messageBox");

                if(mBox && (button = mBox.querySelector("button"))) {
                    button.addEventListener("click", hide);
                }
            }

            if(mBox) {
                if(lastAddedClass) {
                    mBox.classList.remove(lastAddedClass);
                }
                if(className) {
                    mBox.classList.add(className);
                }
                lastAddedClass = className;
            }

            mBox.innerHTML = msg;
            mBox.appendChild(button);
            mBox.classList.add("display");

            if(timeoutId) {
                window.clearTimeout(timeoutId);
            }
            timeoutId = window.setTimeout(hide, 5000);

        };

        return {
            show: show,
            hide: hide
        };
    };

    this.vxWeb.routes.publish = "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('publishXhr')->getUrl() ?>";
	this.vxWeb.routes.filter = "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('articles')->getUrl(['action' => 'list']) ?>";

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
	    var checkboxSort = function(a, b) {

            var checked1 = a.element.cells[this.ndx].querySelector("input").checked,
                checked2 = b.element.cells[this.ndx].querySelector("input").checked;

            if(checked1 === checked2) {
                return 0;
            }
            if(this.asc) {
                return checked2 ? -1 : 1;
            }
            else {
                return checked1 ? -1 : 1;
            }
        };

        var lsValue, lsKey = window.location.origin + "/admin/articles__sort__",
			t = vxJS.widget.sorTable(
                document.querySelector(".table"),	{
				columnFormat: [
					null,
					null,
					checkboxSort,
                    checkboxSort,
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
            <th data-column-name="customflags"></th>
			<th data-column-name="article_date"></th>
			<th data-column-name="display_from"></th>
			<th data-column-name="display_until"></th>
			<th data-column-name="customsort"></th>
			<th data-column-name="lastupdated"></th>
			<th></th>
		</tr>
		<tr>
			<th class="col-2 vx-sortable-header">Kategorie</th>
			<th class="vx-sortable-header">Titel</th>
			<th class="vx-sortable-header">Pub</th>
            <th class="vx-sortable-header">*</th>
			<th class="col-1 vx-sortable-header">Artikeldatum</th>
			<th class="col-1 vx-sortable-header">Anzeige von</th>
			<th class="col-1 vx-sortable-header">Anzeige bis</th>
			<th class="vx-sortable-header">Sortierziffer</th>
			<th class="col-2 vx-sortable-header">Angelegt/aktualisiert</th>
			<th class="col-1"></th>
		</tr>
	</thead>
</table>