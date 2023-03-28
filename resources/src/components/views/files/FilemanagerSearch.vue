<script setup>
  import Spinner from "@/components/misc/spinner.vue";
  import Modal from "@/components/vx-vue/modal.vue";
  import { Focus } from "@/directives/focus";
  import { EllipsisHorizontalIcon, FolderIcon, MagnifyingGlassIcon, XMarkIcon } from "@heroicons/vue/24/solid";
  import { urlQueryCreate } from "@/util/url-query";
</script>

<template>
  <teleport to="#search-input" v-if="isMounted">
    <button type="button" class="icon-link flex items-center" @click="showDialog = !showDialog"><magnifying-glass-icon class="h-5 w-5" /> Suchen...</button>
  </teleport>

  <modal :show="showDialog">
    <template #title>
      <div class="fixed flex w-full justify-between items-center bg-vxvue-500 h-16 px-4">
        <div class="flex items-center space-x-2 w-full">
          <input
              class="form-input w-1/2"
              :value="modelValue"
              :placeholder="placeholder"
              @keydown.esc="handleEsc"
              @input="handleInput"
              @focus="handleInput"
              v-focus
          />
          <spinner class="h-5 w-5 text-vxvue" v-if="busy" />
        </div>
        <a href="#" @click.prevent="handleEsc"><x-mark-icon class="w-5 h-5 text-white"/></a>
      </div>
    </template>

    <template #default>
      <div class="pt-16">
        <div v-for="(folder, ndx) in (folders || [])" :key="folder.id" :class="['px-4 py-2', { 'bg-slate-100': ndx % 2 }]">
          <div class="flex items-center space-x-2">
            <folder-icon class="h-5 w-5" />
            <a
                :href="'#' + folder.path"
                @click.prevent="pickFolder(folder)"
            >{{ folder.name }}</a>
          </div>
        </div>
        <div class="flex justify-center py-2" v-if="files.length && folders.length">
          <ellipsis-horizontal-icon class="h-5 w-5 text-slate-700" />
        </div>
        <div v-for="(file, ndx) in (files || [])" :key="file.id" :class="['px-4 py-2', { 'bg-slate-100': ndx % 2 }]">
          <div class="flex space-x-2 items-center">
            <div class="w-1/2"><div>{{ file.name }}</div><div class="text-sm text-slate-700">{{ file.type }}</div></div>
            <div class="w-1/2 flex items-center space-x-2">
              <folder-icon class="h-5 w-5" />
              <a
                  :href="'/' + file.folder.path"
                  @click.prevent="pickFolder(file.folder)"
              >{{ file.folder.path }}</a>
            </div>
          </div>

        </div>
      </div>
    </template>
  </modal>
</template>

<script>
import {Focus} from "@/directives/focus";

export default {
  name: 'FilemanagerSearch',
  inject: ['api'],
  emits: ['folder-picked'],
  data() {
    return {
      modelValue: "",
      files: [],
      folders: [],
      busy: false,
      showDialog: false
    }
  },

  props: {
    isMounted: { type: Boolean, default: false },
    placeholder: { type: String, default: 'Datei/Verzeichnis suchen...' },
    minLength: { type: Number, default: 3 }
  },

  methods: {
    async handleInput (event) {
      this.modelValue = event.target.value;
      let term = this.modelValue.trim();
      if (term.length >= this.minLength) {
        this.busy = true;
        let response = await this.$fetch(urlQueryCreate(this.api + "files/search", { search: term }));
        this.files = response.files || [];
        this.folders = response.folders || [];
        this.busy = false;
      }
      else {
        this.files = [];
        this.folders = [];
      }
    },
    handleEsc () {
      this.modelValue = "";
      this.files = [];
      this.folders = [];
      this.showDialog = false;
    },
    pickFolder (id) {
      this.$emit('folder-picked', id);
      this.handleEsc();
    }
  },
  directives: {
    focus: Focus
  }
}
</script>