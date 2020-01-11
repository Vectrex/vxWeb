<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>Dateien</h1>

    <div class="vx-button-bar navbar">
        <div class="navbar-section">
            <span class="btn-group">
                <button class="btn" v-for="breadcrumb in breadcrumbs">{{ breadcrumb.name }}</button>
            </span>
        </div>
        <div class="navbar-section">
            <button class="btn with-webfont-icon-right btn-primary" type="button" data-icon="&#xe007;" @click="addFolder">Verzeichnis anlegen</button>
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
            <a :href="'#' + slotProps.row.key" v-if="slotProps.row.isFolder" @click.prevent="getFolder(slotProps.row.key)">{{ slotProps.row.name }}</a>
            <template v-else>{{ slotProps.row.name }}</template>
        </template>
        <template v-slot:action="slotProps">
            <button v-if="slotProps.row.isFolder" class="btn webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="delFolder(slotProps.row)">&#xe008;</button>
            <template v-else>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="editFile(slotProps.row)">&#xe002;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="moveFile(slotProps.row)">&#xe004;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="delFile(slotProps.row)">&#xe011;</button>
            </template>
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
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('files_init')->getUrl() ?>",
            editFile: "",
            delFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_del')->getUrl() ?>",
            moveFile: "",
            delFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_del')->getUrl() ?>",
            addFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_add')->getUrl() ?>"
        },

        data: {
            files: [],
            folders: [],
            breadcrumbs: [],
            cols: [
                {
                    label: "Dateiname",
                    sortable: true,
                    prop: "name",
                    sortAscFunction: (a, b) => {
                        if (a.isFolder && !b.isFolder) {
                            return -1;
                        }
                        return a.name.toLowerCase() === b.name.toLowerCase() ? 0 : a.name.toLowerCase() < b.name.toLowerCase() ? -1 : 1;
                    },
                    sortDescFunction: (a, b) => {
                        if (a.isFolder && !b.isFolder) {
                            return -1;
                        }
                        return a.name.toLowerCase() === b.name.toLowerCase() ? 0 : a.name.toLowerCase() < b.name.toLowerCase() ? 1 : -1;
                    }
                },
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
            async delFile (row) {
                if(window.confirm("Datei '" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(this.$options.routes.delFile + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.files.splice(this.files.findIndex(item => row === item), 1);
                    }
                }
            },
            async delFolder (row) {
                console.log(row);
                if(window.confirm("Ordner und Inhalt von '" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(this.$options.routes.delFolder + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.folders.splice(this.folders.findIndex(item => row === item), 1);
                    }
                }
            },
            addFolder () {
            },
            async moveFile (row) {
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