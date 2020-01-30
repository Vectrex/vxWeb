<!-- {extend: admin/layout_with_menu.php @ content_block } -->

<div id="app"
     v-cloak
     @drop.prevent="uploadFile"
     @dragover.prevent="indicateDrag = true"
     @dragleave.prevent="indicateDrag = false"
     :class="{'dragged-over': indicateDrag}">

    <h1>Dateien</h1>

    <div class="vx-button-bar navbar">
        <section class="navbar-section">
            <span class="btn-group">
                <button
                    v-for="breadcrumb in breadcrumbs"
                    class="btn"
                    :key="breadcrumb.key"
                    :class="{'active': breadcrumb.key === currentFolder.key }"
                    @click="readFolder(breadcrumb)">{{ breadcrumb.name }}</button>
            </span>
        </section>
        <section class="navbar-section">
            <template v-if="uploadInProgress">
                <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Abbrechen" type="button" @click="cancelUpload">&#xe01d;</button>
                <label class="d-inline-block mr-2">{{ progress.file }}</label>
                <progress class="progress" :value="progress.loaded" :max="progress.total"></progress>
            </template>
            <strong class="text-primary d-block col-12 text-center" v-else>Uploads hierher ziehen</strong>
        </section>
        <section class="navbar-section">
            <input
                v-if="showAddFolderInput"
                v-focus
                class="form-input"
                @keydown.enter="addFolder"
                @keydown.esc="showAddFolderInput = false"
                @blur="showAddFolderInput = false"
                ref="addFolderInput">
            <button
                v-if="!showAddFolderInput"
                class="btn with-webfont-icon-right btn-primary"
                type="button"
                data-icon="&#xe007;"
                @click="showAddFolderInput = true">Verzeichnis anlegen</button>
        </section>
    </div>

    <sortable
        :rows="directoryEntries"
        :columns="cols"
        :sort-prop="initSort.column"
        :sort-direction="initSort.dir"
        @after-sort="storeSort"
        ref="sortable">
        <template v-slot:name="slotProps">
            <template v-if="slotProps.row.isFolder">
                <input
                    v-if="slotProps.row === toRename"
                    v-focus
                    class="form-input"
                    :value="slotProps.row.name"
                    @keydown.enter="renameFolder"
                    @keydown.esc="toRename = null"
                    @blur="toRename = null"
                >
                <template v-else>
                    <a :href="'#' + slotProps.row.key" @click.prevent="readFolder(slotProps.row)">{{ slotProps.row.name }}</a>
                    <button class="btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2" data-tooltip="Umbenennen" @click="toRename = slotProps.row">&#xe001;</button>
                </template>
            </template>
            <template v-else>
                <input
                    v-if="slotProps.row === toRename"
                    v-focus
                    class="form-input"
                    :value="slotProps.row.name"
                    @keydown.enter="renameFile"
                    @keydown.esc="toRename = null"
                    @blur="toRename = null"
                >
                <template v-else>
                    <span>{{ slotProps.row.name }}</span>
                    <button class="btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2" data-tooltip="Umbenennen" @click="toRename = slotProps.row">&#xe001;</button>
                </template>
            </template>
        </template>

        <template v-slot:action="slotProps">
            <button v-if="slotProps.row.isFolder" class="btn webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="delFolder(slotProps.row)">&#xe008;</button>
            <template v-else>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="editFile(slotProps.row)">&#xe002;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="getFolderTree(slotProps.row)">&#xe004;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="delFile(slotProps.row)">&#xe011;</button>
            </template>
        </template>

        <template v-slot:size="slotProps">{{ slotProps.row.size | formatFilesize(',') }}</template>
        <template v-slot:type="slotProps">
            <img :src="slotProps.row.src" alt="" v-if="slotProps.row.image">
            <span v-else>{{ slotProps.row.type }}</span>
        </template>
    </sortable>

    <div class="modal active" v-if="showEditForm">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <a href="#close" class="btn btn-clear float-right" aria-label="Close" @click.prevent="showEditForm = false"></a>
            </div>
            <div class="modal-body">
                <file-edit-form
                    :initial-data="editFormData"
                    :file-info="editFileInfo"
                    :url="$options.routes.updateFile"
                    @response-received="handleEdit"
                    ref="editForm"
                />
            </div>
        </div>
    </div>

    <div class="modal active" v-if="showFolderTree">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <a href="#close" class="btn btn-clear float-right" aria-label="Close" @click.prevent="showFolderTree = false"></a>
                <div class="modal-title h5">Zielordner wählen&hellip;</div>
            </div>
            <div class="modal-body">
                <simple-tree :branch="root" @branch-selected="moveToFolder"></simple-tree>
            </div>
        </div>
    </div>

    <message-toast
        :message="toastProps.message"
        :classname="toastProps.messageClass"
        :active="toastProps.isActive"
        ref="toast"
    />

</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>

    const Sortable = window.vxweb.default.Sortable;
    const MessageToast = window.vxweb.default.MessageToast;
    const SimpleTree = window.vxweb.default.SimpleTree;
    const FileEditForm = window.vxweb.default.FileEditForm;
    const SimpleFetch = window.vxweb.default.SimpleFetch;
    const PromisedXhr = window.vxweb.default.PromisedXhr;

    // simple directive to enable event bubbling

    Vue.directive('bubble', (el, binding, vnode) => {
        Object.keys(binding.modifiers).forEach(event => {
            // Bubble events of Vue components
            if (vnode.componentInstance) {
                vnode.componentInstance.$off(event);
                vnode.componentInstance.$on(event, (...args) => { vnode.context.$emit(event, ...args); });
                // Bubble events of native DOM elements
            }
            else {
                el.addEventListener(event, payload => { vnode.context.$emit(event, payload); });
            }
        })
    });

    let app = new Vue({

        el: "#app",
        components: { "sortable": Sortable, 'message-toast': MessageToast, 'file-edit-form': FileEditForm, 'simple-tree': SimpleTree },

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('files_init')->getUrl() ?>",
            readFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_read')->getUrl() ?>",
            getFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_get')->getUrl() ?>",
            updateFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_update')->getUrl() ?>",
            uploadFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_upload')->getUrl() ?>",
            delFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_del')->getUrl() ?>",
            renameFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_rename')->getUrl() ?>",
            moveFile: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('file_move')->getUrl() ?>",
            getFoldersTree: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folders_tree')->getUrl() ?>",
            delFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_del')->getUrl() ?>",
            renameFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_rename')->getUrl() ?>",
            addFolder: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('folder_add')->getUrl() ?>"
        },

        data: {
            root: {},
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
            toRename: null,
            toMove: null,
            showAddFolderInput: false,
            showEditForm: false,
            showFolderTree: false,
            indicateDrag: false,
            uploads: [],
            uploadInProgress: false,
            cancelUploadToken: {},
            progress: { total: null, loaded: null, file: null },
            editFormData: {},
            editFileInfo: {},
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            }
        },

        computed: {
            directoryEntries() {
                let folders = this.folders;
                folders.forEach(item => { item.isFolder = true, item.key = 'd_' + item.key });
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
                    this.files = response.files || [];
                    this.folders = response.folders || [];
                    this.currentFolder = row;
                    if(!this.breadcrumbs) {
                        return;
                    }
                    if(
                        response.breadcrumbs.length >= this.breadcrumbs.length ||
                        this.breadcrumbs.map(item => item.key).join().indexOf(response.breadcrumbs.map(item => item.key).join()) !== 0
                    ) {
                        this.breadcrumbs = response.breadcrumbs;
                    }
                }
            },
            async editFile (row) {
                this.showEditForm = true;
                let response = await SimpleFetch(this.$options.routes.getFile + '?id=' + row.key);
                this.editFormData = response.form || {};
                this.editFileInfo = response.fileInfo || {};
                this.editFormData.id = row.key;
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
                if(name && this.toRename) {
                    let response = await SimpleFetch(this.$options.routes.renameFile, 'POST', {}, JSON.stringify({name: name, id: this.toRename.key }));
                    if(response.success) {
                        this.toRename.name = response.name || name;
                        this.toRename = null;
                    }
                }
            },
            async renameFolder (event) {
                let name = event.target.value.trim();
                if(name && this.toRename) {
                    let response = await SimpleFetch(this.$options.routes.renameFolder, 'POST', {}, JSON.stringify({name: name, id: this.toRename.key }));
                    if(response.success) {
                        let ndx = this.breadcrumbs.findIndex(item => item.key === this.toRename.key);
                        if (ndx !== -1) {
                            this.breadcrumbs[ndx].name = response.name;
                        }
                        this.toRename.name = response.name || name;
                        this.toRename = null;
                    }
                }
            },
            async delFolder (row) {
                if(window.confirm("Ordner und Inhalt von '" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(this.$options.routes.delFolder + '?id=' + row.key.substr(2), 'DELETE');
                    if(response.success) {
                        this.folders.splice(this.folders.findIndex(item => row === item), 1);
                        let ndx = this.breadcrumbs.findIndex(item => item.key === row.key);
                        if (ndx !== -1) {
                            this.breadcrumbs.splice(ndx);
                        }
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
            handleEdit: function () {
                let response = this.$refs.editForm.response;

                this.toastProps = {
                    message: response.message,
                    messageClass: response.success ? 'toast-success' : 'toast-error'
                };
                this.$refs.toast.isActive = true;
            },
            async getFolderTree (row) {
                this.toMove = row;
                let response = await SimpleFetch(this.$options.routes.getFoldersTree + '?id=' + this.currentFolder.key);
                this.showFolderTree = true;
                this.root = response;
            },
            async moveToFolder (folder) {
                if(this.toMove) {
                    let response = await SimpleFetch(this.$options.routes.moveFile, 'POST', {}, JSON.stringify({
                        id: this.toMove.key,
                        folderId: folder.key
                    }));
                    if (response.success) {
                        this.files.splice(this.files.findIndex(item => this.toMove === item), 1);
                        this.toMove = null;
                        this.showFolderTree = false;
                    }
                    else {
                        this.toastProps = {
                            message: response.message,
                            messageClass: 'toast-error'
                        };
                        this.$refs.toast.isActive = true;
                    }
                }
            },
            uploadFile (event) {
                this.indicateDrag = false;
                let droppedFiles = event.dataTransfer.files;

                if (!droppedFiles) {
                    return;
                }
                [...droppedFiles].forEach(f => this.uploads.push(f));

                if(!this.uploadInProgress) {
                    this.uploadInProgress = true;
                    this.handleUploads();
                }
            },
            async handleUploads () {
                let file = null, response = null;
                while((file = this.uploads.shift()) !== undefined) {
                    this.progress.file = file.name;
                    try {
                        response = await PromisedXhr(
                            this.$options.routes.uploadFile + '?id=' + this.currentFolder.key,
                            'POST',
                            {
                                'Content-type': file.type || 'application/octet-stream',
                                'X-File-Name': file.name.replace(/[^\x00-\x7F]/g, c => encodeURIComponent(c)),
                                'X-File-Size': file.size,
                                'X-File-Type': file.type
                            },
                            file,
                            null,
                            e => {
                                this.progress.total = e.total;
                                this.progress.loaded = e.loaded;
                            },
                            this.cancelUploadToken
                        );
                        this.files = response.files || [];
                    } catch(err) {
                        this.uploads = [];
                        this.uploadInProgress = false;
                        return;
                    }

                    if(!response.success) {
                        this.toastProps = {
                            message: response.message || 'Ein Fehler ist beim Upload aufgetreten.',
                            messageClass: 'toast-error'
                        };
                        this.$refs.toast.isActive = true;
                        this.uploads = [];
                        this.uploadInProgress = false;
                        return;
                    }
                }

                this.toastProps = {
                    message: response.message || 'Upload erfolgreich.',
                    messageClass: 'toast-success'
                };
                this.$refs.toast.isActive = true;
                this.uploadInProgress = false;
            },
            cancelUpload () {
                if(this.cancelUploadToken.cancel) {
                    this.cancelUploadToken.cancel();
                    this.cancelUploadToken = {};
                }
            },
            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/files__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        },

        filters: {
            formatFilesize(size, sep) {
                if(!size) {
                    return '';
                }
                let i = Math.floor(Math.floor(Math.log(size) / Math.log(1000)));
                return (size / Math.pow(1000, i)).toFixed(i ? 2 : 0).toString().replace('.', sep || '.') + ['B', 'kB', 'MB', 'GB'][i];
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