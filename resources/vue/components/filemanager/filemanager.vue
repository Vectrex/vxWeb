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
        <filemanager-breadcrumbs
            :breadcrumbs="breadcrumbs"
            :current-folder="currentFolder"
            :folders="folders"
            @breadcrumb-clicked="readFolder"
        ></filemanager-breadcrumbs>
        <div class="popup popup-bottom ml-1" :class="{ active: showAddActivities }">
          <button class="btn webfont-icon-only" type="button" @click.stop="showAddActivities = !showAddActivities">
            &#xe020;
          </button>
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
          <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Abbrechen" type="button"
                  @click="cancelUpload">&#xe01d;
          </button>
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
            <span class="with-webfont-icon-left" data-icon=""><a :href="'#' + slotProps.folder.id"
                                                                  @click.prevent="readFolder(slotProps.folder.id)">{{
                slotProps.folder.name
              }}</a></span>
          </template>
          <template v-slot:file="slotProps">
            <span class="with-webfont-icon-left" data-icon="">{{ slotProps.file.name }} ({{
                slotProps.file.type
              }})</span><br>
            <a :href="'#' + slotProps.file.folder"
               @click.prevent="readFolder(slotProps.file.folder)">{{ slotProps.file.path }}</a>
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
        <label class="form-checkbox"><input type="checkbox"
          @click="[...folders, ...files].forEach(item => item.checked = $event.target.checked);" v-check-indeterminate><i
            class="form-icon"></i></label>
      </template>

      <template v-slot:checked="slotProps">
        <label class="form-checkbox"><input type="checkbox" v-model="slotProps.row.checked"><i
            class="form-icon"></i></label>
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
            <button class="btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2"
                    data-tooltip="Umbenennen" @click="toRename = slotProps.row">&#xe001;
            </button>
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
            <button class="btn webfont-icon-only tooltip mr-1 rename display-only-on-hover ml-2"
                    data-tooltip="Umbenennen" @click="toRename = slotProps.row">&#xe001;
            </button>
          </template>
        </template>
      </template>

      <template v-slot:size="slotProps">
        <template v-if="!slotProps.row.isFolder">{{ formatFilesize(slotProps.row.size, ',') }}</template>
      </template>

      <template v-slot:type="slotProps">
        <img :src="slotProps.row.src" alt="" v-if="slotProps.row.image">
        <span v-else>{{ slotProps.row.type }}</span>
      </template>

      <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
        <slot :name="name" v-bind="slotData"/>
      </template>
    </sortable>
    <div class="modal active" v-if="showFileForm || showFolderForm">
      <div class="modal-overlay"></div>
      <div class="modal-container">
        <div class="modal-header">
          <a href="#close" class="btn btn-clear float-right" aria-label="Close"
             @click.prevent="showFileForm = showFolderForm = false"></a>
        </div>
        <div class="modal-body">
          <file-edit-form
              :initial-data="editFormData"
              :file-info="editMetaData"
              :url="routes.updateFile"
              @response-received="response => $emit('response-received', response)"
              v-if="showFileForm"
          />
          <folder-edit-form
              :initial-data="editFormData"
              :folder-info="editMetaData"
              :url="routes.updateFolder"
              @response-received="response => $emit('response-received', response)"
              v-if="showFolderForm"
          />
        </div>
      </div>
    </div>
    <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
    <alert ref="alert" :config="{ label: 'Ok', buttonClass: 'btn-error' }"></alert>
    <folder-tree ref="folder-tree"/>
  </div>
</template>

<script>
import FilemanagerAdd from './filemanager-add';
import FilemanagerActions from './filemanager-actions';
import FilemanagerSearch from './filemanager-search';
import FilemanagerBreadcrumbs from './filemanager-breadcrumbs';
import FilemanagerFolderTree from './filemanager-folder-tree';
import Sortable from '../vx-vue/sortable';
import CircularProgress from '../circular-progress';
import Confirm from '../vx-vue/confirm';
import Alert from '../vx-vue/alert';
import FileEditForm from '../forms/file-edit-form';
import FolderEditForm from '../forms/folder-edit-form';
import SimpleFetch from "../../util/simple-fetch";
import PromisedXhr from "../../util/promised-xhr";
import UrlQuery from "../../util/url-query";
import FolderTree from "./filemanager-folder-tree";
import { formatFilesize } from '../../filters';
import { Focus } from "../../directives";

export default {
  name: 'filemanager',
  components: {
    FolderTree,
    'sortable': Sortable,
    'circular-progress': CircularProgress,
    'confirm': Confirm,
    'alert': Alert,
    'file-edit-form': FileEditForm,
    'folder-edit-form': FolderEditForm,
    'filemanager-add': FilemanagerAdd,
    'filemanager-search': FilemanagerSearch,
    'filemanager-actions': FilemanagerActions,
    'filemanager-breadcrumbs': FilemanagerBreadcrumbs,
    'filemanager-folder-tree': FilemanagerFolderTree
  },

  data() {
    return {
      currentFolder: null,
      files: [],
      folders: [],
      breadcrumbs: [],
      toRename: null,
      showAddActivities: false,
      indicateDrag: false,
      upload: { files: [], progressing: false, cancelToken: {} },
      progress: { total: null, loaded: null, file: null },
      showFileForm: false,
      showFolderForm: false,
      editFormData: {},
      editMetaData: {}
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
    },
    checkedFiles() {
      return this.files.filter(({checked}) => checked);
    },
    checkedFolders() {
      return this.folders.filter(({checked}) => checked);
    }
  },

  props: {
    routes: { type: Object, required: true },
    limits: { type: Object, default: () => ({}) },
    columns: { type: Array, required: true },
    folder: { type: String, default: '' },
    initSort: Object
  },

  watch: {
    folder(newValue) {
      this.currentFolder = newValue;
    }
  },

  async created() {
    let response = await SimpleFetch(UrlQuery.create(this.routes.init, {folder: this.folder}));

    this.breadcrumbs = response.breadcrumbs || [];
    this.files = response.files || [];
    this.folders = response.folders || [];
    this.currentFolder = response.currentFolder || null;
  },
  mounted() {
    document.body.addEventListener('click', this.handleBodyClick);
  },
  beforeUnmount() {
    document.body.removeEventListener('click', this.handleBodyClick);
  },

  methods: {
    handleBodyClick() {
      this.showAddActivities = false;
    },
    async readFolder(id) {
      let response = await SimpleFetch(UrlQuery.create(this.routes.readFolder, {folder: id}));

      if (response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
        this.currentFolder = id;
        if (response.breadcrumbs) {
          this.breadcrumbs = response.breadcrumbs;
        }
      }
    },
    async delSelection() {
      let response = await SimpleFetch(UrlQuery.create(this.routes.delSelection, {
        files: this.checkedFiles.map(({id}) => id).join(","),
        folders: this.checkedFolders.map(({id}) => id).join(",")
      }), 'DELETE');
      if (response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
      } else if (response.error) {
        this.$emit('response-received', response);
        this.files = response.files || this.files;
        this.folders = response.folders || this.folders;
      }
    },
    async moveSelection() {
      let folder = await this.$refs['folder-tree'].open(this.routes.getFoldersTree, this.currentFolder);

      if (folder !== false) {
        let response = await SimpleFetch(UrlQuery.create(this.routes.moveSelection, {destination: folder.id}), 'POST', {}, JSON.stringify({
          files: this.checkedFiles.map(({id}) => id),
          folders: this.checkedFolders.map(({id}) => id)
        }));

        if (response.success) {
          this.files = response.files || [];
          this.folders = response.folders || [];
        } else if (response.error) {
          this.$emit('response-received', response);
          this.files = response.files || this.files;
          this.folders = response.folders || this.folders;
        }
      }
    },
    async editFile(row) {
      this.showFileForm = true;
      let response = await SimpleFetch(UrlQuery.create(this.routes.getFile, {id: row.id}));
      this.editFormData = response.form || {};
      this.editMetaData = response.fileInfo || {};
      this.editFormData.id = row.id;
    },
    async editFolder(row) {
      this.showFolderForm = true;
      let response = await SimpleFetch(UrlQuery.create(this.routes.getFolder, {id: row.id}));
      this.editFormData = response.form || {};
      this.editMetaData = response.folderInfo || {};
      this.editFormData.id = row.id;
    },
    async delFile(row) {
      if (await this.$refs.confirm.open('Datei löschen', "'" + row.name + "' wirklich löschen?")) {
        let response = await SimpleFetch(UrlQuery.create(this.routes.delFile, {id: row.id}), 'DELETE');
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        }
      }
    },
    async renameFile(event) {
      let name = event.target.value.trim();
      if (name && this.toRename) {
        let response = await SimpleFetch(this.routes.renameFile, 'POST', {}, JSON.stringify({
          name: name,
          id: this.toRename.id
        }));
        if (response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async renameFolder(event) {
      let name = event.target.value.trim();
      if (name && this.toRename) {
        let response = await SimpleFetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({
          name: name,
          folder: this.toRename.id
        }));
        if (response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async delFolder(row) {
      if (await this.$refs.confirm.open('Verzeichnis löschen', "'" + row.name + "' und enthaltene Dateien wirklich löschen?", {cancelLabel: "Abbrechen"})) {
        let response = await SimpleFetch(UrlQuery.create(this.routes.delFolder, {folder: row.id}), 'DELETE');
        if (response.success) {
          this.folders.splice(this.folders.findIndex(item => row === item), 1);
        }
      }
    },
    async createFolder(name) {
      this.showAddActivities = false;

      let response = await SimpleFetch(this.routes.addFolder, 'POST', {}, JSON.stringify({
        name: name,
        parent: this.currentFolder
      }));
      if (response.folder) {
        this.folders.push(response.folder);
      }
    },
    async moveFile(row) {
      let folder = await this.$refs['folder-tree'].open(this.routes.getFoldersTree, this.currentFolder);

      if (folder !== false) {
        let response = await SimpleFetch(this.routes.moveFile, 'POST', {}, JSON.stringify({
          id: row.id,
          folderId: folder.id
        }));
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        } else {
          this.$emit('response-received', response);
        }
      }
    },
    uploadDraggedFiles(event) {
      this.indicateDrag = false;
      this.uploadInputFiles(event.dataTransfer.files || []);
    },
    uploadInputFiles(files) {
      this.showAddActivities = false;
      this.upload.files.push(...files);
      if (!this.upload.progressing) {
        this.upload.progressing = true;
        this.progress.loaded = 0;
        this.handleUploads();
      }
    },
    async handleUploads() {
      let file = null, response = null;
      while ((file = this.upload.files.shift()) !== undefined) {

        if (this.limits.maxUploadFilesize && this.limits.maxUploadFilesize < file.size) {
          await this.$refs.alert.open('Datei zu groß', "'" + file.name + "' übersteigt die maximale Uploadgröße.");
          continue;
        }
        this.progress.file = file.name;
        try {
          response = await PromisedXhr(
              UrlQuery.create(this.routes.uploadFile, {folder: this.currentFolder}),
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
        } catch (err) {
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }

        if (!response.success) {
          this.$emit('response-received', response);
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }
      }
      this.upload.progressing = false;
      if (response) {
        this.$emit('response-received', {success: true, message: response.message || 'File upload successful'});
      }
    },
    cancelUpload() {
      if (this.upload.cancelToken.cancel) {
        this.upload.cancelToken.cancel();
        this.upload.cancelToken = {};
      }
    },
    doSearch(term) {
      if (term.trim().length > 2) {
        return SimpleFetch(UrlQuery.create(this.routes.search, {search: term}));
      }
      return {files: [], folders: []};
    },
    formatFilesize: formatFilesize
  },

  directives: {
    focus: Focus,
    checkIndeterminate: {
      updated(el, binding, vnode) {
        let filteredLength = binding.instance.checkedFolders.length + binding.instance.checkedFiles.length;
        if (!filteredLength) {
          el.checked = false;
        }
        el.indeterminate = filteredLength && filteredLength !== binding.instance.folders.length + binding.instance.files.length;
      }
    }
  }
}
</script>