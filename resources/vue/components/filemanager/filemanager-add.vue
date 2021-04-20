<template>
  <div>
    <input
        v-if="showAddFolderInput"
        v-focus
        class="form-input"
        @keydown.enter="addFolder"
        @keydown.esc="showAddFolderInput = false"
        @blur="showAddFolderInput = false"
    >
    <button
        v-if="!showAddFolderInput"
        class="btn with-webfont-icon-left btn-link"
        type="button"
        data-icon=""
        @click.stop="showAddFolderInput = true"
    >Verzeichnis erstellen
    </button>
    <label class="btn with-webfont-icon-left btn-link" data-icon="" for="file_upload">Datei hochladen</label>
    <input type="file" id="file_upload" class="d-none" :multiple="multiple" @change="fileChanged"/>
  </div>
</template>

<script>
import {Focus} from "../../directives";

export default {
  name: 'filemanager-add',
  emits: ['upload', 'create-folder'],
  props: {
    multiple: {type: Boolean, default: true}
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