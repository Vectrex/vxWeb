<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import FileEditForm from "@/components/views/files/FileEditForm.vue";
  import FolderEditForm from "@/components/views/files/FolderEditForm.vue";
  import FilemanagerActions from "@/components/views/files/FilemanagerActions.vue";
  import FilemanagerAdd from "@/components/views/files/FilemanagerAdd.vue";
  import FilemanagerBreadcrumbs from "@/components/views/files/FilemanagerBreadcrumbs.vue";
  import FilemanagerSearch from "@/components/views/files/FilemanagerSearch.vue";
  import FolderTree from "@/components/views/files/FolderTree.vue";
  import Headline from "@/components/app/Headline.vue";
  import Alert from "@/components/vx-vue/alert.vue";
  import CircularProgress from "@/components/misc/circular-progress.vue";
  import Modal from "@/components/vx-vue/modal.vue";
  import { PencilSquareIcon, PlusIcon, XMarkIcon } from '@heroicons/vue/24/solid';
  import { urlQueryCreate } from '@/util/url-query';
  import { formatFilesize } from "@/util/format-filesize";
  import { Focus } from "@/directives/focus";
</script>
<template>
  <teleport to="#tools">
    <div class="relative">
      <headline>
        <span>Dateien</span>
        <button
          type="button"
          :class="['icon-link text-vxvue-100 border-vxvue-100 hover:border-vxvue-200']"
          href="#" @click.stop="showAddActivities = !showAddActivities"
        >
          <plus-icon class="w-5 h-5" />
        </button>
      </headline>
      <transition name="appear">
          <div
            v-if="showAddActivities"
            class="absolute right-0 z-10 mt-2 origin-top-right rounded bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
          >
          <filemanager-add
              @upload="uploadInputFiles"
              @create-folder="createFolder"
          />
        </div>
      </transition>
    </div>
  </teleport>

  <div
      v-cloak
      @drop.prevent="uploadDraggedFiles"
      @dragover.prevent="indicateDrag = true"
      @dragleave.prevent="indicateDrag = false"
      :class="{'border-4 border-vxvue-alt': indicateDrag }"
  >
    <div class="flex pb-4 justify-between items-center">
      <filemanager-breadcrumbs
          :breadcrumbs="breadcrumbs"
          :current-folder="currentFolder"
          :folders="folders"
          @breadcrumb-clicked="readFolder"
      />
      <filemanager-actions
          @delete-selection="delSelection"
          @move-selection="moveSelection"
          :files="checkedFiles"
          :folders="checkedFolders"
      />
      <div v-if="upload.progressing" class="flex space-x-2 items-center">
        <button class="icon-link" data-tooltip="Abbrechen" type="button" @click="cancelUpload"><x-mark-icon class="h-5 w-5" /></button>
        <strong>{{ progress.file }}</strong>
        <circular-progress :progress="100 * progress.loaded / (progress.total || 1)" :radius="16" />
      </div>
      <strong class="text-primary d-block col-12 text-center" v-else>Dateien zum Upload hierher ziehen</strong>

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
    </div>

    <sortable
        :rows="directoryEntries"
        :columns="columns"
        :sort-prop="initSort.column"
        :sort-direction="initSort.dir"
        @after-sort="$emit('after-sort', { sortColumn: $refs.sortable.sortColumn, sortDir: $refs.sortable.sortDir })"
        ref="sortable"
    >

      <template v-slot:checked-header>
        <input type="checkbox"
          class="form-checkbox"
          @click="[...folders, ...files].forEach(item => item.checked = $event.target.checked)"
          ref="multiCheckbox"
        />
      </template>

      <template v-slot:checked="slotProps">
        <input type="checkbox" class="form-checkbox" v-model="slotProps.row.checked" />
      </template>

      <template v-slot:name="slotProps">
        <div class="flex items-center space-x-1 group">
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
              <button
                  class="icon-link opacity-0 group-hover:opacity-100 transition-opacity tooltip"
                  data-tooltip="Umbenennen"
                  @click="toRename = slotProps.row"
              >
                <pencil-square-icon class="h-5 w-5" />
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
              <button
                class="icon-link opacity-0 group-hover:opacity-100 transition-opacity tooltip"
                data-tooltip="Umbenennen"
                @click="toRename = slotProps.row"
              >
                <pencil-square-icon class="h-5 w-5" />
              </button>
            </template>
          </template>
        </div>
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
  </div>

  <div class="modal active" v-if="showFileForm || showFolderForm">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <div class="modal-header">
        <a href="#" class="btn btn-clear float-right" aria-label="Close"
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

  <teleport to="body">
    <alert
        ref="confirm"
        :buttons="[
            { label: 'Löschen!', value: true, 'class': 'button alert' },
            { label: 'Abbrechen', value: false, 'class': 'button' }
          ]"
    />
    <alert
        ref="alert"
        :buttons="[
            { label: 'Ok!', value: true, 'class': 'button alert' },
          ]"
    />
    <modal :show="showFolderTree">
      <folder-tree ref="folderTree" />
    </modal>
  </teleport>
</template>

<script>
export default {
  name: 'Filemanager',
  inject: ['api'],
  emits: ['response-received', 'after-sort'],
  expose: ['delFile', 'delFolder', 'editFile', 'editFolder', 'moveFile'],
  props: {
    routes: { type: Object, required: true },
    columns: { type: Array, required: true },
    folder: { type: String, default: '' },
    initSort: Object
  },

  data() {
    return {
      limits: {},
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
      showFolderTree: false,
      editFormData: {},
      editMetaData: {}
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
      return this.files.filter(({checked}) => checked);
    },
    checkedFolders () {
      return this.folders.filter(({checked}) => checked);
    }
  },

  watch: {
    folder(newValue) {
      this.currentFolder = newValue;
    },
    checkedFiles (newValue) {
      let filteredLength = this.checkedFolders.length + newValue.length;
      if (!filteredLength) {
        this.$refs.multiCheckbox.checked = false;
      }
      this.$refs.multiCheckbox.indeterminate = filteredLength && filteredLength !== this.files.length + this.folders.length;
    },
    checkedFolders (newValue) {
      let filteredLength = this.checkedFiles.length + newValue.length;
      if (!filteredLength) {
        this.$refs.multiCheckbox.checked = false;
      }
      this.$refs.multiCheckbox.indeterminate = filteredLength && filteredLength !== this.files.length + this.folders.length;
    }
  },

  async created() {
    let response = await this.$fetch(urlQueryCreate(this.api + this.routes.init, { folder: this.folder }));

    this.breadcrumbs = response.breadcrumbs || [];
    this.files = response.files || [];
    this.folders = response.folders || [];
    this.currentFolder = response.currentFolder || null;
    this.limits = response.limits || {};
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
      let response = await this.$fetch(this.api + 'folder/' + id +'/read');

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
      let response = await this.$fetch(urlQueryCreate(this.routes.delSelection, {
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
        let response = await this.$fetch(urlQueryCreate(this.routes.moveSelection, {destination: folder.id}), 'POST', {}, JSON.stringify({
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
      let response = await this.$fetch(urlQueryCreate(this.routes.getFile, {id: row.id}));
      this.editFormData = response.form || {};
      this.editMetaData = response.fileInfo || {};
      this.editFormData.id = row.id;
    },
    async editFolder(row) {
      this.showFolderForm = true;
      let response = await this.$fetch(urlQueryCreate(this.routes.getFolder, {id: row.id}));
      this.editFormData = response.form || {};
      this.editMetaData = response.folderInfo || {};
      this.editFormData.id = row.id;
    },
    async delFile(row) {
      if (await this.$refs.confirm.open('Datei löschen', "'" + row.name + "' wirklich löschen?")) {
        let response = await this.$fetch(this.api + 'file/' + row.id, 'DELETE');
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        }
      }
    },
    async renameFile(event) {
      let name = event.target.value.trim();
      if (name && this.toRename) {
        let response = await this.$fetch(this.routes.renameFile, 'POST', {}, JSON.stringify({
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
        let response = await this.$fetch(this.routes.renameFolder, 'POST', {}, JSON.stringify({
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
        let response = await this.$fetch(this.api + 'folder/' + row.id, 'DELETE');
        if (response.success) {
          this.folders.splice(this.folders.findIndex(item => row === item), 1);
        }
        this.$emit('response-received', response);
      }
    },
    async createFolder(name) {
      this.showAddActivities = false;
      let response = await this.$fetch(this.api + 'folder', 'POST', {}, JSON.stringify({
        name: name,
        parent: this.currentFolder
      }));
      if (response.folder) {
        this.folders.push(response.folder);
      }
      this.$emit('response-received', response);
    },
    async moveFile(row) {
      this.showFolderTree = true;
      this.$nextTick(
          async () => {
            let folder = await this.$refs.folderTree.open(this.api + this.routes.getFoldersTree, this.currentFolder);
            this.showFolderTree = false;

            if (folder !== false) {
              let response = await this.$fetch(this.api + this.routes.moveFile, 'PUT', {}, JSON.stringify({
                id: row.id,
                folderId: folder.id
              }));
              if (response.success) {
                this.files.splice(this.files.findIndex(item => row === item), 1);
              }
              this.$emit('response-received', response);
            }
          }
      );
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
          response = await this.$promisedXhr(
              this.api + "file?folder=" + this.currentFolder,
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
          if (response.status >= 400) {
              this.$router.replace({ name: 'login' });
          }
          else {
            this.files = response.files || this.files;
          }
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
        this.$emit('response-received', { success: true, message: response.message || 'File upload successful' });
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
        return this.$fetch(urlQueryCreate(this.routes.search, {search: term}));
      }
      return {files: [], folders: []};
    },
    formatFilesize: formatFilesize
  },

  directives: {
    focus: Focus
  }
}
</script>