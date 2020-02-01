<template>
    <div
        v-cloak
        @drop.prevent="uploadFile"
        @dragover.prevent="indicateDrag = true"
        @dragleave.prevent="indicateDrag = false"
        :class="{'dragged-over': indicateDrag}"
    >
        <div class="vx-button-bar navbar">
            <section class="navbar-section">
            <span class="btn-group">
                <button
                        v-for="breadcrumb in breadcrumbs"
                        class="btn"
                        :key="breadcrumb.id"
                        :class="{'active': breadcrumb.id === currentFolder.id }"
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
                        data-icon=""
                        @click="showAddFolderInput = true">Verzeichnis anlegen</button>
            </section>
        </div>
        <sortable
                :rows="directoryEntries"
                :columns="columns"
                :sort-prop="initSort.column"
                :sort-direction="initSort.dir"
                @after-sort="$emit('after-sort', { sortColumn: $refs.sortable.sortColumn, sortDir: $refs.sortable.sortDir })"
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
                        <a :href="'#' + slotProps.row.id" @click.prevent="readFolder(slotProps.row)">{{ slotProps.row.name }}</a>
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

    </div>
</template>

<script>
    import Sortable from './sortable';
    import SimpleTree from './simple-tree';
    import FileEditForm from './forms/file-edit-form';
    import SimpleFetch from "../util/simple-fetch";
    import PromisedXhr from "../util/promised-xhr";
    import { formatFilesize } from '../filters';
    import { Focus } from "../directives";

    export default {
        components: {
            'sortable': Sortable, 'simple-tree': SimpleTree, 'file-edit-form': FileEditForm
        },

        data () {
            return {
                root: {},
                currentFolder: {},
                files: [],
                folders: [],
                breadcrumbs: [],
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
                editFileInfo: {}
            }
        },

        computed: {
            directoryEntries() {
                let folders = this.folders;
                let files = this.files;
                folders.forEach(item => {
                    item.isFolder = true;
                    item.key = 'd' + item.id
                });
                files.forEach(item => item.key = item.id);
                return [...folders, ...files];
            }
        },

        props: {
            routes: { type: Object, required: true },
            columns: { type: Array, required: true },
            initSort: { type: Object }
        },

        async created () {
            let response = await SimpleFetch(this.routes.init);

            this.breadcrumbs = response.breadcrumbs || [];
            this.files = response.files || [];
            this.folders = response.folders || [];
            this.currentFolder = response.currentFolder;
        },

        methods: {
            async readFolder (row) {
                let response = await SimpleFetch(this.routes.readFolder + '?id=' + row.id);

                if(response.success) {
                    this.files = response.files || [];
                    this.folders = response.folders || [];
                    this.currentFolder = row;
                    if(!this.breadcrumbs) {
                        return;
                    }
                    if(
                        response.breadcrumbs.length >= this.breadcrumbs.length ||
                        this.breadcrumbs.map(item => item.id).join().indexOf(response.breadcrumbs.map(item => item.id).join()) !== 0
                    ) {
                        this.breadcrumbs = response.breadcrumbs;
                    }
                }
            },
            async editFile (row) {
                this.showEditForm = true;
                let response = await SimpleFetch(this.routes.getFile + '?id=' + row.id);
                this.editFormData = response.form || {};
                this.editFileInfo = response.fileInfo || {};
                this.editFormData.id = row.id;
            },
            async delFile (row) {
                if(window.confirm("Datei '" + row.name + "' wirklich löschen?")) {
                    let response = await SimpleFetch(this.routes.delFile + '?id=' + row.id, 'DELETE');
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
                    let response = await SimpleFetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({name: name, id: this.toRename.id }));
                    if(response.success) {
                        let ndx = this.breadcrumbs.findIndex(item => item.id === this.toRename.id);
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
                    let response = await SimpleFetch(this.routes.delFolder + '?id=' + row.id, 'DELETE');
                    if(response.success) {
                        this.folders.splice(this.folders.findIndex(item => row === item), 1);
                        let ndx = this.breadcrumbs.findIndex(item => item.id === row.id);
                        if (ndx !== -1) {
                            this.breadcrumbs.splice(ndx);
                        }
                    }
                }
            },
            async addFolder () {
                let name = this.$refs.addFolderInput.value.trim();
                if(name) {
                    let response = await SimpleFetch(this.routes.addFolder, 'POST', {}, JSON.stringify({name: name, parent: this.currentFolder.id }));
                    if(response.success) {
                        this.showAddFolderInput = false;
                    }
                    if(response.folder) {
                        this.folders.push(response.folder);
                    }
                }
            },
            async getFolderTree (row) {
                this.toMove = row;
                let response = await SimpleFetch(this.routes.getFoldersTree + '?id=' + this.currentFolder.id);
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
                            this.routes.uploadFile + '?id=' + this.currentFolder.id,
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
                        this.$emit('response-received', response);
                        this.uploads = [];
                        this.uploadInProgress = false;
                        return;
                    }
                }
                this.$emit('response-received', { success: true, message: response.message || 'File upload successful' });
                this.uploadInProgress = false;
            },
            cancelUpload () {
                if(this.cancelUploadToken.cancel) {
                    this.cancelUploadToken.cancel();
                    this.cancelUploadToken = {};
                }
            }
        },

        directives: {
            focus: Focus,
        },

        filters: {
            formatFilesize: formatFilesize
        }
    }
</script>