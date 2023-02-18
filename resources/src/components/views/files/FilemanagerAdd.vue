<script setup>
  import { Focus } from "@/directives/focus";
  import { DocumentArrowUpIcon, FolderPlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
  <div>
    <input
        v-if="showAddFolderInput"
        v-focus
        class="form-input"
        @keydown.enter="addFolder"
        @keydown.esc="showAddFolderInput = false"
        @blur="showAddFolderInput = false"
    />
    <button
        v-else
        type="button"
        @click.stop="showAddFolderInput = true"
        class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex space-x-1 items-center"
    >
      <folder-plus-icon class="w-5 h-5" />
      <span class="">Verzeichnis&nbsp;erstellen</span>
    </button>
    <label
      for="file_upload"
      class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex space-x-1 items-center"
    >
      <document-arrow-up-icon class="w-5 h-5" />
      <span>Datei hochladen</span>
    </label>
    <input type="file" id="file_upload" class="hidden" :multiple="multiple" @change="fileChanged"/>
  </div>
</template>

<script>
export default {
  name: 'FilemanagerAdd',
  emits: ['upload', 'create-folder'],
  props: {
    multiple: { type: Boolean, default: true }
  },
  data() {
    return {
      showAddFolderInput: false
    }
  },

  methods: {
    fileChanged(event) {
      const files = event.target.files || event.dataTransfer.files;
      if (files) {
        this.$emit('upload', files);
      }
    },
    addFolder(event) {
      const name = event.target.value.trim();
      if (name) {
        this.$emit('create-folder', name);
      }
      this.showAddFolderInput = false;
    }
  },

  directives: {
    focus: Focus
  }
}
</script>