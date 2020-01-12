<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>Dateien</h1>

    <div class="vx-button-bar navbar">
        <div class="navbar-section">
            <span class="btn-group">
                <button
                    v-for="breadcrumb in breadcrumbs"
                    class="btn"
                    :key="breadcrumb.key"
                    :class="{'active': breadcrumb.key === currentFolder.key }"
                    @click="readFolder(breadcrumb)">{{ breadcrumb.name }}</button>
            </span>
        </div>
        <div class="navbar-section">
            <input
                v-if="showAddFolderInput"
                v-focus
                class="form-input"
                @keydown.enter="addFolder"
                @keydown.esc="showAddFolderInput = false"
                ref="addFolderInput">
            <button
                v-if="!showAddFolderInput"
                class="btn with-webfont-icon-right btn-primary"
                type="button"
                data-icon="&#xe007;"
                @click="showAddFolderInput = true">Verzeichnis anlegen</button>
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
            <template v-if="slotProps.row.isFolder">
                <input
                    v-if="slotProps.row === renaming"
                    v-focus
                    class="form-input"
                    :value="slotProps.row.name"
                    @keydown.enter="renameFolder"
                    @keydown.esc="renaming = null"
                    @blur="renaming = null"
                >
                <template v-else>
                    <a :href="'#' + slotProps.row.key" v-if="" @click.prevent="readFolder(slotProps.row)">{{ slotProps.row.name }}</a>
                    <button class="btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2" data-tooltip="Umbenennen" @click="renaming = slotProps.row">&#xe001;</button>
                </template>
            </template>
            <template v-else>
                <input
                    v-if="slotProps.row === renaming"
                    v-focus
                    class="form-input"
                    :value="slotProps.row.name"
                    @keydown.enter="renameFile"
                    @keydown.esc="renaming = null"
                    @blur="renaming = null"
                >
                <template v-else>
                    <span :title="slotProps.row.title">{{ slotProps.row.name }}</span>
                    <button class="btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2" data-tooltip="Umbenennen" @click="renaming = slotProps.row">&#xe001;</button>
                </template>
            </template>
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

    <div class="modal active" v-if="showEditForm">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <a href="#close" class="btn btn-clear float-right" aria-label="Close" @click.prevent="showEditForm = false"></a>
            </div>
            <div class="modal-body">
                <!--
                <edit-form
                    :options="options"
                    :url="$options.routes.editUrl"
                    :initial-data="edit"
                    @response-received="editResponseReceived"
                    ref="editForm"
                />
                -->
            </div>
        </div>
    </div>

    <message-toast
        :message="toastProps.message"
        :classname="toastProps.messageClass"
        :active="toastProps.isActive"
        ref="toast"
    ></message-toast>

</div>

<script type="module">
    import Sortable from  "/js/vue/components/sortable.js";
    import SimpleFetch from  "/js/vue/util/simple-fetch.js";
    import MessageToast from "/js/vue/components/message-toast.js";

    let app = new Vue({

        el: "#app",
        components: { "sortable": Sortable, 'message-toast': MessageToast },

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('files_init')->getUrl() ?>",
            readFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_read')->getUrl() ?>",
            editFile: "",
            delFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_del')->getUrl() ?>",
            renameFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_rename')->getUrl() ?>",
            moveFile: "",
            delFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_del')->getUrl() ?>",
            renameFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_rename')->getUrl() ?>",
            addFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_add')->getUrl() ?>"
        },

        data: {
            currentFolder: {},
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
            initSort: {},
            showAddFolderInput: false,
            renaming: null,
            showEditForm: false,
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            }
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
            this.currentFolder = response.currentFolder;
        },

        methods: {
            async readFolder (row) {
                let response = await SimpleFetch(this.$options.routes.readFolder + '?id=' + row.key);

                if(response.success) {
                    this.breadcrumbs = response.breadcrumbs || [];
                    this.files = response.files || [];
                    this.folders = response.folders || [];
                    this.currentFolder = row;
                }
            },
            async editFile (row) {
                this.showEditForm = true;
            },
            async delFile (row) {
                if(window.confirm("Datei '" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(this.$options.routes.delFile + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.files.splice(this.files.findIndex(item => row === item), 1);
                    }
                }
            },
            async renameFile (event) {
                let name = event.target.value.trim();
                if(name && this.renaming) {
                    let response = await SimpleFetch(this.$options.routes.renameFile, 'POST', {}, JSON.stringify({name: name, id: this.renaming.key }));
                    if(response.success) {
                        this.renaming.name = response.name || name;
                        this.renaming = null;
                    }
                }
            },
            async renameFolder (event) {
                let name = event.target.value.trim();
                if(name && this.renaming) {
                    let response = await SimpleFetch(this.$options.routes.renameFolder, 'POST', {}, JSON.stringify({name: name, id: this.renaming.key }));
                    if(response.success) {
                        this.renaming.name = response.name || name;
                        this.renaming = null;
                    }
                }
            },
            async delFolder (row) {
                if(window.confirm("Ordner und Inhalt von '" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(this.$options.routes.delFolder + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.folders.splice(this.folders.findIndex(item => row === item), 1);
                    }
                }
            },
            async addFolder () {
                let name = this.$refs.addFolderInput.value.trim();
                if(name) {
                    let response = await SimpleFetch(this.$options.routes.addFolder, 'POST', {}, JSON.stringify({name: name, parent: this.currentFolder.key }));
                    if(response.success) {
                        this.showAddFolderInput = false;
                    }
                    if(response.folder) {
                        this.folders.push(response.folder);
                    }
                }
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
        },

        directives: {
            focus: {
                inserted (el) {
                    el.focus();
                }
            }
        }
    });
</script>