<script setup>
  import Spinner from "@/components/misc/spinner.vue";
  import Modal from "@/components/vx-vue/modal.vue";
  import FormTitle from "@/components/views/shared/FormTitle.vue";
</script>
<template>
  <teleport to="#search-input">
    <div class="flex items-center space-x-2">
      <spinner class="h-5 w-5 text-vxvue" v-if="busy" />
      <input class="form-input" @keydown.esc="handleEsc" @input="handleInput" @focus="handleInput" v-bind="inputProps">
    </div>
  </teleport>

  <modal :show="showResults">
    <form-title @cancel="handleEsc">Gefundene Dateien und Ordner&hellip;</form-title>
    <div>
      <div v-for="result in (results.folders || [])" :key="result.id">
        <slot name="folder" :folder="result">
          {{ result.name }}
        </slot>
      </div>
      <div v-for="result in (results.files || [])" :key="result.id">
        <slot name="file" :file="result">
          {{ result.name }} ({{ result.type }})<br>
          <span class="text-gray">{{ result.path }}</span>
        </slot>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  name: 'FilemanagerSearch',
  data() {
    return {
      value: "",
      results: {
        files: [],
        folders: []
      },
      busy: false,
      hideDialog: false
    }
  },

  props: {
    placeholder: { type: String, default: 'Datei/Verzeichnis suchen...' },
    search: { type: Function, required: true }
  },

  computed: {
    inputProps() {
      return {
        value: this.value,
        placeholder: this.placeholder
      }
    },
    showResults: {
      get() {
        return this.results.folders.length > 0 || this.results.files.length > 0;
      },
      set(newValue) {
        if (!newValue) {
          this.results.folders = [];
          this.results.files = [];
        }
      }
    }
  },

  methods: {
    handleInput (event) {
      this.value = event.target.value;
      const search = this.search(this.value);

      if (search instanceof Promise) {
        this.busy = true;
        search.then(({files, folders}) => {
          this.results.files = files || [];
          this.results.folders = folders || [];
          this.busy = false;
        });
      } else {
        this.results = search;
      }
    },
    handleEsc() {
      this.value = "";
      this.showResults = false;
    }
  }
}
</script>