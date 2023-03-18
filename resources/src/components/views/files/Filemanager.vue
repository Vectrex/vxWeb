<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import FileEditForm from "@/components/views/files/FileEditForm.vue";
  import FolderEditForm from "@/components/views/files/FolderEditForm.vue";
  import FilemanagerActions from "@/components/views/files/FilemanagerActions.vue";
  import FilemanagerAdd from "@/components/views/files/FilemanagerAdd.vue";
  import FilemanagerBreadcrumbs from "@/components/views/files/FilemanagerBreadcrumbs.vue";
  import FilemanagerSearch from "@/components/views/files/FilemanagerSearch.vue";
  import FolderTree from "@/components/views/files/FolderTree.vue";
  import Alert from "@/components/vx-vue/alert.vue";
  import { PencilSquareIcon, PlusIcon, XMarkIcon } from '@heroicons/vue/24/solid';
  import { urlQueryCreate } from '@/util/url-query';
  import { formatFilesize } from "@/util/format-filesize";
  import { Focus } from "@/directives/focus";
</script>
<template>
  <div
      v-cloak
      @drop.prevent.stop="uploadDraggedFiles"
      @dragover.prevent.stop="indicateDrag = true"
      @dragleave.prevent.stop="indicateDrag = false"
      :class="{'border-4 border-vxvue-alt': indicateDrag }"
  >
    <div class="flex pb-4 justify-between items-center h-16">
      <div class="flex items-center space-x-4">
        <filemanager-breadcrumbs
            :breadcrumbs="breadcrumbs"
            :current-folder="currentFolder"
            :folders="folders"
            @breadcrumb-clicked="readFolder"
        />
        <div class="relative">
          <button
              class="icon-link !text-vxvue-700 border-transparent !hover:border-vxvue-700"
              type="button"
              href="#" @click.stop="showAddActivities = !showAddActivities"
          >
            <plus-icon class="w-5 h-5" />
          </button>
          <transition name="appear">
            <div
                v-show="showAddActivities"
                class="absolute left-0 z-10 mt-2 origin-top-right rounded bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
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
        <filemanager-actions
            @delete-selection="delSelection"
            @move-selection="moveSelection"
            :files="checkedFiles"
            :folders="checkedFolders"
            v-if="checkedFolders.length || checkedFiles.length"
        />
      </div>

      <div class="flex space-x-2 items-center" v-if="upload.progressing">
        <button class="icon-link" data-tooltip="Abbrechen" type="button" @click="cancelUpload"><x-mark-icon class="h-5 w-5" /></button>
        <div class="flex flex-col items-center space-y-2">
          <div class="text-sm">{{ progress.file }}</div>
          <div class="w-64 bg-slate-200 rounded-full h-2">
            <div class="bg-vxvue-500 rounded-full h-full" :style="{ width: (100 * progress.loaded / (progress.total || 1)) + '%' }" />
          </div>
        </div>
      </div>
      <strong class="text-primary d-block col-12 text-center" v-else>Dateien zum Upload hierher ziehen</strong>

      <div id="search-input" />
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
                @keydown.enter="rename($event, 'folder')"
                @keydown.esc="toRename = null"
                @blur="toRename = null"
            >
            <template v-else>
              <a :href="'#' + slotProps.row.id" @click.prevent="readFolder(slotProps.row)">{{ slotProps.row.name }}</a>
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
                @keydown.enter="rename($event, 'file')"
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
        <img :src="slotProps.row.src" alt="" v-if="slotProps.row.image" class="thumb">
        <span v-else>{{ slotProps.row.type }}</span>
      </template>

      <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
        <slot :name="name" v-bind="slotData"/>
      </template>

    </sortable>
  </div>

  <teleport to="body">
    <transition name="fade">
      <div
        class="z-10 fixed right-0 bottom-0 top-24 left-64 bg-black/20 backdrop-blur-sm"
        v-if="formShown"
        @click.stop="formShown = null"
      />
    </transition>

    <transition name="slide-from-right">
      <folder-edit-form
        :id="pickedId"
        v-if="formShown === 'editFolder'"
        @cancel="formShown = null"
        @response-received="$emit('response-received', $event)"
        class="fixed top-24 bottom-0 shadow-gray shadow-lg bg-white w-sidebar right-0 z-50"
      />
    </transition>

    <transition name="slide-from-right">
      <file-edit-form
        :id="pickedId"
        v-if="formShown === 'editFile'"
        @cancel="formShown = null"
        @response-received="$emit('response-received', $event)"
        class="fixed top-24 bottom-0 shadow-gray shadow-lg bg-white w-sidebar right-0 z-50"
      />
    </transition>

    <transition name="slide-from-right">
      <folder-tree
        v-if="formShown === 'folderTree'"
        class="fixed top-24 bottom-0 shadow-gray shadow-lg bg-white w-sidebar right-0 z-50"
        ref="folderTree"
      />
    </transition>

    <alert
        ref="confirm"
        header-class="bg-error text-white"
        :buttons="[
            { label: 'Löschen!', value: true, 'class': 'button alert' },
            { label: 'Abbrechen', value: false, 'class': 'button cancel' }
          ]"
    />
    <alert
        ref="alert"
        header-class="bg-error text-white"
        :buttons="[
            { label: 'Ok!', value: true, 'class': 'button cancel' },
          ]"
    />
  </teleport>

  <filemanager-search @folder-picked="readFolder($event)" :is-mounted="isMounted" />

</template>

<script>
export default {
  name: 'Filemanager',
  inject: ['api'],
  emits: ['response-received', 'after-sort'],
  expose: ['delFile', 'delFolder', 'editFile', 'editFolder', 'moveFile'],
  props: {
    columns: { type: Array, required: true },
    folder: { type: Object, default: {} },
    initSort: Object,
    requestParameters: { type: Object, default: {} }
  },

  data() {
    return {
      isMounted: false,
      limits: {},
      currentFolder: null,
      files: [],
      folders: [],
      breadcrumbs: [],
      toRename: null,
      showAddActivities: false,
      indicateDrag: false,
      formShown: null,
      pickedId: null,
      upload: { files: [], progressing: false, cancelToken: {} },
      progress: { total: null, loaded: null, file: null }
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
    folder: {
      handler (newValue) {
        this.readFolder(newValue);
        this.currentFolder = newValue?.id;
      },
      immediate: true
    },
    checkedFiles (newValue) {
      this.setMultiCheckbox(this.checkedFolders.length + newValue.length);
    },
    checkedFolders (newValue) {
      this.setMultiCheckbox(this.checkedFiles.length + newValue.length);
    }
  },
  mounted() {
    document.body.addEventListener('click', this.handleBodyClick);
    this.isMounted = true;
  },
  beforeUnmount() {
    document.body.removeEventListener('click', this.handleBodyClick);
  },

  methods: {
    setMultiCheckbox (itemCount) {
      if (this.$refs.multiCheckbox) {
        if (!itemCount) {
          this.$refs.multiCheckbox.checked = false;
        }
        this.$refs.multiCheckbox.indeterminate = itemCount && itemCount !== this.files.length + this.folders.length;
      }
    },
    handleBodyClick() {
      this.showAddActivities = false;
    },
    async readFolder(folder) {

      let response = await this.$fetch(urlQueryCreate(this.api + 'folder/' + (folder?.id || '-') + '/read', this.requestParameters));

      if (response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
        this.currentFolder = response.currentFolder?.key || null;
        this.breadcrumbs = response.breadcrumbs || this.breadcrumbs;
        this.limits = response.limits || this.limits;
      }
    },
    async delSelection() {
      let response = await this.$fetch(urlQueryCreate(this.api + "filesfolders/delete", {
        files: this.checkedFiles.map(({id}) => id).join(","),
        folders: this.checkedFolders.map(({id}) => id).join(","),
        ...this.requestParameters
      }), 'DELETE');
      if (response.success) {
        this.files = response.files || [];
        this.folders = response.folders || [];
      } else if (response.error) {
        this.files = response.files || this.files;
        this.folders = response.folders || this.folders;
      }
      this.$emit('response-received', {...response, _method: 'delSelection' });
    },
    moveSelection() {
      this.formShown = 'folderTree';
      this.$nextTick(
        async () => {
          let folder = await this.$refs['folderTree'].open(urlQueryCreate(this.api + 'folders/tree', this.requestParameters), this.currentFolder);
          this.formShown = null;

          if (folder !== false) {
            let response = await this.$fetch(urlQueryCreate(this.api + 'filesfolders/moveto/' + folder.id , this.requestParameters), 'PUT', {}, JSON.stringify({
              files: this.checkedFiles.map(({id}) => id),
              folders: this.checkedFolders.map(({id}) => id)
            }));

            if (response.success) {
              this.files = response.files || [];
              this.folders = response.folders || [];
            } else if (response.error) {
              this.files = response.files || this.files;
              this.folders = response.folders || this.folders;
            }
            this.$emit('response-received', {...response, _method: 'moveSelection' });
          }
        }
      );
    },
    editFile(row) {
      this.formShown = 'editFile';
      this.pickedId = row.id;
    },
    editFolder(row) {
      this.formShown = 'editFolder';
      this.pickedId = row.id;
    },
    async delFile(row) {
      if (await this.$refs.confirm.open('Datei löschen', "'" + row.name + "' wirklich löschen?")) {
        let response = await this.$fetch(urlQueryCreate(this.api + 'file/' + row.id, this.requestParameters), 'DELETE');
        if (response.success) {
          this.files.splice(this.files.findIndex(item => row === item), 1);
        }
        this.$emit('response-received', {...response, _method: 'delFile' });
      }
    },
    async rename(event, type) {
      let name = event.target.value.trim();
      if (name && this.toRename) {
        let response = await this.$fetch(urlQueryCreate(this.api + type + '/' + this.toRename.id + '/rename', this.requestParameters), 'PUT', {}, JSON.stringify({
          name: name
        }));
        if (response.success) {
          this.toRename.name = response.name || name;
          this.toRename = null;
        }
      }
    },
    async delFolder(row) {
      if (await this.$refs.confirm.open('Verzeichnis löschen', "'" + row.name + "' und enthaltene Dateien wirklich löschen?", {cancelLabel: "Abbrechen"})) {
        let response = await this.$fetch(urlQueryCreate(this.api + 'folder/' + row.id, this.requestParameters), 'DELETE');
        if (response.success) {
          this.folders.splice(this.folders.findIndex(item => row === item), 1);
        }
        this.$emit('response-received', {...response, _method: 'delFolder' });
      }
    },
    async createFolder(name) {
      this.showAddActivities = false;
      let response = await this.$fetch(urlQueryCreate(this.api + 'folder', this.requestParameters), 'POST', {}, JSON.stringify({
        name: name,
        parent: this.currentFolder
      }));
      if (response.folder) {
        this.folders.push(response.folder);
      }
      this.$emit('response-received', {...response, _method: 'createFolder' });
    },
    moveFile(row) {
      this.formShown = 'folderTree';
      this.$nextTick(
          async () => {
            let folder = await this.$refs.folderTree.open(urlQueryCreate(this.api + 'folders/tree', this.requestParameters), this.currentFolder);
            this.formShown = null;

            if (folder !== false) {
              let response = await this.$fetch(urlQueryCreate(this.api + 'file/' + row.id + '/move', this.requestParameters), 'PUT', {}, JSON.stringify({
                folderId: folder.id
              }));
              if (response.success) {
                this.files.splice(this.files.findIndex(item => row === item), 1);
              }
              this.$emit('response-received', {...response, _method: 'moveFile' });
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
        if (this.limits.maxUploadSize && this.limits.maxUploadSize < file.size) {
          await this.$refs.alert.open('Datei zu groß', "'" + file.name + "' übersteigt die maximale Uploadgröße.");
          continue;
        }
        this.progress.file = file.name;
        try {
          response = await this.$promisedXhr(
              urlQueryCreate(this.api + "file?folder=" + this.currentFolder, this.requestParameters),
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
          this.$emit('response-received', {...response, _method: 'uploadFiles' });
          this.upload.files = [];
          this.upload.progressing = false;
          return;
        }
      }
      this.upload.progressing = false;
      if (response) {
        this.$emit('response-received', { success: true, message: response.message || 'File upload successful', _method: 'uploadFiles' });
      }
    },
    cancelUpload() {
      if (this.upload.cancelToken.cancel) {
        this.upload.cancelToken.cancel();
        this.upload.cancelToken = {};
      }
    },
    formatFilesize: formatFilesize
  },

  directives: {
    focus: Focus
  }
}
</script>