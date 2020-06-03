<template>
    <div
        v-cloak
        @drop.prevent="uploadDraggedFiles"
        @dragover.prevent="indicateDrag = true"
        @dragleave.prevent="indicateDrag = false"
        :class="{'dragged-over': indicateDrag}"
    >
        <div class="vx-button-bar navbar">
            <section class="navbar-section">
                <filemanager-breadcrumbs :breadcrumbs="breadcrumbs" :current-folder="currentFolder" :folders="folders" @breadcrumb-clicked="readFolder"></filemanager-breadcrumbs>
                <div class="popup popup-bottom ml-1" :class="{ active: showAddActivities }">
                    <button class="btn webfont-icon-only" type="button" @click.stop="showAddActivities = !showAddActivities">&#xe020;</button>
                    <div class="popup-container">
                        <div class="card">
                            <div class="card-body">
                                <filemanager-add
                                    @upload="uploadInputFiles"
                                    @create-folder="createFolder"
                                ></filemanager-add>
                            </div>
                        </div>
                    </div>
                </div>
                <filemanager-actions
                    @delete-selection="delSelection"
                    @move-selection="moveSelection"
                    :files="checkedFiles"
                    :folders="checkedFolders"
                ></filemanager-actions>
            </section>

            <section class="navbar-section">
                <template v-if="upload.progressing">
                    <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Abbrechen" type="button" @click="cancelUpload">&#xe01d;</button>
                    <label class="d-inline-block mr-2">{{ progress.file }}</label>
                    <circular-progress :progress="100 * progress.loaded / (progress.total || 1)" :radius="16"></circular-progress>
                </template>
                <strong class="text-primary d-block col-12 text-center" v-else>Dateien zum Upload hierher ziehen</strong>
            </section>

            <section class="navbar-section">
                <filemanager-search
                    :search="doSearch"
                >
                    <template v-slot:folder="slotProps">
                        <span class="with-webfont-icon-left" data-icon=""><a :href="'#' + slotProps.folder.id" @click.prevent="readFolder(slotProps.folder.id)">{{ slotProps.folder.name }}</a></span>
                    </template>
                    <template v-slot:file="slotProps">
                        <span class="with-webfont-icon-left" data-icon="">{{ slotProps.file.name }} ({{ slotProps.file.type }})</span><br>
                        <a :href="'#' + slotProps.file.folder" @click.prevent="readFolder(slotProps.file.folder)">{{ slotProps.file.path }}</a>
                    </template>

                </filemanager-search>
            </section>
        </div>
        <sortable
            :rows="directoryEntries"
            :columns="columns"
            :sort-prop="initSort.column"
            :sort-direction="initSort.dir"
            @after-sort="$emit('after-sort', { sortColumn: $refs.sortable.sortColumn, sortDir: $refs.sortable.sortDir })"
            ref="sortable"
            id="files-list"
        >
            <template v-slot:checked-header>
                <label class="form-checkbox"><input type="checkbox" @click="toggleAll" v-check-indeterminate><i class="form-icon"></i></label>
            </template>

            <template v-slot:checked="slotProps">
                <label class="form-checkbox"><input type="checkbox" v-model="slotProps.row.checked"><i class="form-icon"></i></label>
            </template>

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
                        <a :href="'#' + slotProps.row.id" @click.prevent="readFolder(slotProps.row.id)">{{ slotProps.row.name }}</a>
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

            <template v-slot:size="slotProps"><template v-if="!slotProps.row.isFolder">{{ slotProps.row.size | formatFilesize(',') }}</template></template>

            <template v-slot:type="slotProps">
                <img :src="slotProps.row.src" alt="" v-if="slotProps.row.image">
                <span v-else>{{ slotProps.row.type }}</span>
            </template>

            <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData"><slot :name="name" v-bind="slotData" /></template>
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
                        :url="routes.updateFile"
                        @response-received="(response) => $emit('response-received', response)"
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

        <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
        <alert ref="alert" :config="{ label: 'Ok', buttonClass: 'btn-error' }"></alert>
    </div>
</template>

<script>
    import FilemanagerAdd  from './filemanager/filemanager-add';
    import FilemanagerActions  from './filemanager/filemanager-actions';
    import FilemanagerSearch  from './filemanager/filemanager-search';
    import FilemanagerBreadcrumbs  from './filemanager/filemanager-breadcrumbs';
    import Sortable from './sortable';
    import SimpleTree from './simple-tree';
    import CircularProgress from './circular-progress';
    import Confirm from './confirm';
    import Alert from './alert';
    import FileEditForm from './forms/file-edit-form';
    import SimpleFetch from "../util/simple-fetch";
    import PromisedXhr from "../util/promised-xhr";
    import UrlQuery from "../util/url-query";
    import { formatFilesize } from '../filters';
    import { Focus } from "../directives";

    export default {
        components: {
            'sortable': Sortable,
            'simple-tree': SimpleTree,
            'circular-progress': CircularProgress,
            'confirm': Confirm,
            'alert': Alert,
            'file-edit-form': FileEditForm,
            'filemanager-add': FilemanagerAdd,
            'filemanager-search': FilemanagerSearch,
            'filemanager-actions': FilemanagerActions,
            'filemanager-breadcrumbs': FilemanagerBreadcrumbs
        },

        data () {
            return {
                root: {},
                currentFolder: null,
                files: [],
                folders: [],
                breadcrumbs: [],
                toRename: null,
                toMove: null,
                showEditForm: false,
                showFolderTree: false,
                showAddActivities: false,
                indicateDrag: false,
                upload: {
                    files: [],
                    progressing: false,
                    cancelToken: {}
                },
                cancelUploadToken: {},
                progress: { total: null, loaded: null, file: null },
                editFormData: {},
                editFileInfo: {}
            }
        },

        computed: {
            directoryEntries () {
                let folders = this.folders;
                let files = this.files;
                folders.forEach(item => {
                    item.isFolder = true;
                    item.key = 'd' + item.id
                });
                files.forEach(item => item.key = item.id);
                return [...folders, ...files];
            },
            checkedFiles () {
                return this.files.filter(item => item.checked);
            },
            checkedFolders () {
                return this.folders.filter(item => item.checked);
            }
        },

        props: {
            routes: { type: Object, required: true },
            limits: { type: Object, default: () => {} },
            columns: { type: Array, required: true },
            folder: { type: String, default: '' },
            initSort: { type: Object }
        },

        watch: {
            folder (newValue) {
                this.currentFolder = newValue;
            }
        },

        async created () {
            let response = await SimpleFetch(UrlQuery.create(this.routes.init, { folder: this.folder }));

            this.breadcrumbs = response.breadcrumbs || [];
            this.files = response.files || [];
            this.folders = response.folders || [];
            this.currentFolder = response.currentFolder || null;
        },
        mounted () {
            document.body.addEventListener('click', this.handleBodyClick);
        },
        beforeDestroy () {
            document.body.removeEventListener('click', this.handleBodyClick);
        },

        methods: {
            handleBodyClick () {
                this.showAddActivities = false;
            },
            toggleAll (event) {
                [...this.folders, ...this.files].forEach(item => this.$set(item, 'checked', event.target.checked));
            },
            async readFolder (id) {
                let response = await SimpleFetch(UrlQuery.create(this.routes.readFolder, { folder: id }));

                if(response.success) {
                    this.files = response.files || [];
                    this.folders = response.folders || [];
                    this.currentFolder = id;
                    if(response.breadcrumbs) {
                        this.breadcrumbs = response.breadcrumbs;
                    }
                }
            },
            async delSelection () {
                let response = await SimpleFetch(UrlQuery.create(this.routes.delSelection, { files: this.checkedFiles.map(item => item.id).join(","), folders: this.checkedFolders.map(item => item.id).join(",") }), 'DELETE');
                if(response.success) {
                    this.files = response.files || [];
                    this.folders = response.folders || [];
                }
                else if(response.error) {
                    this.$emit('response-received', response);
                    this.files = response.files || this.files;
                    this.folders = response.folders || this.folders;
                }
            },
            moveSelection () {
            },
            async editFile (row) {
                this.showEditForm = true;
                let response = await SimpleFetch(UrlQuery.create(this.routes.getFile, { id: row.id }));
                this.editFormData = response.form || {};
                this.editFileInfo = response.fileInfo || {};
                this.editFormData.id = row.id;
            },
            async delFile (row) {
                if(await this.$refs.confirm.open('Datei löschen', "'" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(UrlQuery.create(this.routes.delFile, { id: row.id }), 'DELETE');
                    if(response.success) {
                        this.files.splice(this.files.findIndex(item => row === item), 1);
                    }
                }
            },
            async renameFile (event) {
                let name = event.target.value.trim();
                if(name && this.toRename) {
                    let response = await SimpleFetch(this.routes.renameFile, 'POST', {}, JSON.stringify({name: name, id: this.toRename.id }));
                    if(response.success) {
                        this.toRename.name = response.name || name;
                        this.toRename = null;
                    }
                }
            },
            async renameFolder (event) {
                let name = event.target.value.trim();
                if(name && this.toRename) {
                    let response = await SimpleFetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({name: name, folder: this.toRename.id }));
                    if(response.success) {
                        this.toRename.name = response.name || name;
                        this.toRename = null;
                    }
                }
            },
            async delFolder (row) {
                if(await this.$refs.confirm.open('Verzeichnis löschen', "'" + row.name + "' und enthaltene Dateien wirklich löschen?", { cancelLabel: "Abbrechen" })) {
                    let response = await SimpleFetch(UrlQuery.create(this.routes.delFolder, { folder: row.id }), 'DELETE');
                    if(response.success) {
                        this.folders.splice(this.folders.findIndex(item => row === item), 1);
                    }
                }
            },
            async createFolder (name) {
                this.showAddActivities = false;

                let response = await SimpleFetch(this.routes.addFolder, 'POST', {}, JSON.stringify({ name: name, parent: this.currentFolder }));
                if(response.folder) {
                    this.folders.push(response.folder);
                }
            },
            async getFolderTree (row) {
                this.toMove = row;
                let response = await SimpleFetch(UrlQuery.create(this.routes.getFoldersTree, { folder: this.currentFolder }));
                this.showFolderTree = true;
                this.root = response;
            },
            async moveToFolder (folder) {
                if(this.toMove) {
                    let response = await SimpleFetch(this.routes.moveFile, 'POST', {}, JSON.stringify({
                        id: this.toMove.id,
                        folderId: folder.id
                    }));
                    if (response.success) {
                        this.files.splice(this.files.findIndex(item => this.toMove === item), 1);
                        this.toMove = null;
                        this.showFolderTree = false;
                    }
                    else {
                        this.$emit('response-received', response);
                    }
                }
            },
            uploadDraggedFiles (event) {
                this.indicateDrag = false;
                let files = event.dataTransfer.files;

                if (!files) {
                    return;
                }

                this.uploadInputFiles(files);
            },
            uploadInputFiles (files) {
                this.showAddActivities = false;

                [...files].forEach(f => this.upload.files.push(f));
                if(!this.upload.progressing) {
                    this.upload.progressing = true;
                    this.progress.loaded = 0;
                    this.handleUploads();
                }
            },
            async handleUploads () {
                let file = null, response = null;
                while((file = this.upload.files.shift()) !== undefined) {

                    if(this.limits.maxUploadFilesize && this.limits.maxUploadFilesize < file.size) {
                        await this.$refs.alert.open('Datei zu groß', "'" + file.name + "' übersteigt die maximale Uploadgröße.");
                        continue;
                    }
                    this.progress.file = file.name;
                    try {
                        response = await PromisedXhr(
                            UrlQuery.create(this.routes.uploadFile, { folder: this.currentFolder }),
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
                            this.upload.cancelToken
                        );
                        this.files = response.files || this.files;
                    } catch(err) {
                        this.upload.files = [];
                        this.upload.progressing = false;
                        return;
                    }

                    if(!response.success) {
                        this.$emit('response-received', response);
                        this.upload.files = [];
                        this.upload.progressing = false;
                        return;
                    }
                }
                this.upload.progressing = false;
                if(response) {
                    this.$emit('response-received', { success: true, message: response.message || 'File upload successful' });
                }
            },
            cancelUpload () {
                if(this.upload.cancelToken.cancel) {
                    this.upload.cancelToken.cancel();
                    this.upload.cancelToken = {};
                }
            },
            doSearch (term) {
                if(term.trim().length > 2) {
                    return SimpleFetch(UrlQuery.create(this.routes.search, { search: term }));
                }
                return { files: [], folders: [] };
            }
        },

        directives: {
            focus: Focus,
            checkIndeterminate: {
                update (el, binding, vnode) {
                    let filteredLength = vnode.context.checkedFolders.length + vnode.context.checkedFiles.length;
                    if (!filteredLength) {
                        el.checked = false;
                    }
                    el.indeterminate = filteredLength && filteredLength !== vnode.context.folders.length + vnode.context.files.length;
                }
            }
        },

        filters: {
            formatFilesize: formatFilesize
        }
    }
</script>