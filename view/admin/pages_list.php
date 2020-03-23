<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">
    <h1>Seiten</h1>

    <sortable
        :rows="pages"
        :columns="cols"
        :sort-prop="initSort.column"
        :sort-direction="initSort.dir"
        ref="sortable"
        @after-sort="storeSort"
        id="pages-list"
    >
        <template v-slot:action="slotProps">
            <a class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('page_edit')->getUrl()?>?id=' + slotProps.row.key">&#xe002;</a>
        </template>
    </sortable>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>

    const Sortable = window.vxweb.Components.Sortable;
    const SimpleFetch = window.vxweb.Util.SimpleFetch;

    const app = new Vue({

        el: "#app",
        components: {"sortable": Sortable},

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('pages_init')->getUrl() ?>"
        },

        data: {
            pages: [],
            cols: [
                { label: "Alias/Titel", sortable: true, width: "col-2", prop: "alias" },
                { label: "Datei", sortable: true, width: "col-2", prop: "template" },
                { label: "Inhalt", width: "col-4", prop: "contents" },
                { label: "letzte Änderung", sortable: true, width: "col-2", prop: "updated" },
                { label: "#Rev", sortable: true, width: "col-1", prop: "revisionCount" },
                { label: "", width: "col-1", prop: "action", cssClass: "text-right" }
            ],
            initSort: {}
        },

        async created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/pages__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }

            let response = await SimpleFetch(this.$options.routes.init);

            this.pages = response.pages || [];
        },

        methods: {
            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/pages__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        }

    });
</script>
