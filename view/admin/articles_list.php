<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>Artikel &amp; News</h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$articles/new">Artikel anlegen</a>
        <select v-model="filter.cat" class="form-select">
            <option value="">(Kategorie filtern)</option>
            <option v-for="cat in categories" :value="cat.id">{{ cat.label }}</option>
        </select>
    </div>

    <sortable :rows="filteredArticles" :columns="cols">
        <template v-slot:pub="slotProps">
            <label class="form-switch">
                <input type="checkbox" :checked="slotProps.row.pub" @click="publish(slotProps.row)">
                <i class="form-icon"></i>
            </label>
        </template>
        <template v-slot:marked="slotProps">
            <label class="form-checkbox">
                <input type="checkbox" disabled="disabled" :checked="slotProps.row.marked">
                <i class="form-icon"></i>
            </label>
        </template>
        <template v-slot:action="slotProps">
            <a class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" href="#">&#xe002;</a>
            <a class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" href="#" @click.prevent="del(slotProps.row)">&#xe011;</a>
        </template>
    </sortable>

</div>

<script type="module">
    import Sortable from  "/js/vue/components/sortable.js";
    import SimpleFetch from  "/js/vue/util/simple-fetch.js";

    let app = new Vue({

        el: "#app",
        components: { "sortable": Sortable },

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('articles_init')->getUrl() ?>",
            publish: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_publish')->getUrl() ?>",
            delete: '',
            filter: ''
        },

        data: {
            articles: [],
            categories: [],
            cols: [
                { label: "Kategorie", sortable: true, prop: "cat" },
                { label: "Titel", sortable: true, prop: "title" },
                { label: "Pub", sortable: true, prop: "pub" },
                { label: "*", sortable: true, prop: "marked" },
                { label: "Artikeldatum", sortable: true, prop: "date" },
                { label: "Anzeige von", sortable: true, prop: "from" },
                { label: "Anzeige bis", sortable: true, prop: "until" },
                { label: "Sortierziffer", sortable: true, prop: "sort" },
                { label: "Angelegt/aktualisiert", sortable: true, prop: "updated" },
                { label: "", prop: "action" }
            ],
            initSort: {},
            filter: {
                cat: ''
            }
        },

        computed: {
            filteredArticles() {
                return this.articles.filter(item => !this.filter.cat || this.filter.cat === item.catId);
            }
        },

        async created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/users__sort__");
            if(window.localStorage && lsValue) {
                this.initSort = JSON.parse(lsValue);
            }

            let response = await SimpleFetch(this.$options.routes.init);

            this.categories = response.categories || [];

            let catLookup = {};
            this.categories.forEach(item => catLookup[item.id] = item);
            this.articles = response.articles || [];
            this.articles.forEach(item => item.cat = catLookup[item.catId].label);
        },

        methods: {
            publish (row) {
                console.log(row);
                this.$set(row, 'pub', !row.pub);
            },
            del (row) {
                if(window.confirm('Wirklich löschen?')) {
                    console.log(row);
                    this.articles.splice(this.articles.findIndex(item => row === item), 1);
                }
            }
        }
    });
</script>
<!--
<script type="text/javascript">

	"use strict";

    if(!this.vxWeb) {
        this.vxWeb = {};
    }
    if(!this.vxWeb.routes) {
        this.vxWeb.routes = {};
    }

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
-->