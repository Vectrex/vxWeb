<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">
    <h1>Seiten</h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('page_add')->getUrl() ?>">Seite anlegen</a>
    </div>

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
            <a class="btn btn-link webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('page_edit')->getUrl()?>?id=' + slotProps.row.key">&#xe002;</a>
            <a class="btn btn-link webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" href="#" @click.prevent="del(slotProps.row)">&#xe011;</a>
        </template>
    </sortable>
    <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
</div>

<script>
    const { Sortable, Confirm } = window.vxweb.Components;
    const SimpleFetch = window.vxweb.Util.SimpleFetch;

    Vue.createApp({

        components: { "sortable": Sortable, "confirm": Confirm },

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('pages_init')->getUrl() ?>",
            delete: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('page_del')->getUrl()?>"
        },

        data: () => ({
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
        }),

        async created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/pages__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }

            let response = await SimpleFetch(this.$options.routes.init);

            this.pages = response.pages || [];
        },

        methods: {
            async del (row) {
                if(await this.$refs.confirm.open('Seite löschen', "Soll die Seite mit allen Revisionen wirklich gelöscht werden?")) {
                    let response = await SimpleFetch(this.$options.routes.delete + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.pages.splice(this.pages.findIndex(item => row === item), 1);
                    }
                }
            },
            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/pages__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        }

    }).mount('#app');
</script>
