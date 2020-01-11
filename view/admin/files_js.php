<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>Dateien</h1>

    <div class="vx-button-bar">
        <div class="navbar-section">
            <span class="btn-group">
                <button class="btn" v-for="breadcrumb in breadcrumbs">{{ breadcrumb.name }}</button>
            </span>
        </div>
    </div>

    <sortable
        :rows="directoryEntries"
        :columns="cols"
        :sort-prop="initSort.column"
        :sort-direction="initSort.dir"
        @after-sort="storeSort"
        ref="sortable"
    >
        <template v-slot:name="slotProps">
            <a :href="'#' + slotProps.row.id" v-if="slotProps.row.isFolder" @click.prevent="getFolder(slotProps.row.id)">{{ slotProps.row.name }}</a>
            <template v-else>{{ slotProps.row.name }}</template>
        </template>
        <template v-slot:action="slotProps">
            <button class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="editFile(slotProps.row)" v-if="!slotProps.row.isFolder">&#xe002;</button>
        </template>
        <template v-slot:size="slotProps">
            {{ slotProps.row.size | formatInt('.') }}
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
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('files_init')->getUrl() ?>"
        },

        data: {
            files: [],
            folders: [],
            breadcrumbs: [],
            cols: [
                { label: "Dateiname", sortable: true, prop: "name" },
                { label: "Größe", sortable: true, prop: "size" },
                { label: "Typ/Vorschau", sortable: true, prop: "type" },
                { label: "Erstellt", sortable: true, prop: "modified"},
                { label: "", prop: "action" }
            ],
            initSort: {}
        },

        computed: {
            directoryEntries() {
                let folders = this.folders;
                folders.forEach(item => item.isFolder = true);
                return [...folders, ...this.files];
            }
        },

        async created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/files__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }

            let response = await SimpleFetch(this.$options.routes.init);

            this.breadcrumbs = response.breadcrumbs || [];
            this.files = response.files || [];
            this.folders = response.folders || [];
        },

        methods: {
            async getFolder (id) {
                console.log(id);
            },
            async editFile (row) {

            },
            async del (row) {

            },
            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/files__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        },

        filters: {
            formatInt(size, sep) {

                if(size) {
                    let str = size.toString(), fSize = '';

                    while (str.length > 3) {
                        fSize = (sep || ',') + str.slice(-3) + fSize;
                        str = str.slice(0, -3);
                    }
                    return str + fSize;
                }
            }
        }
    });
</script>