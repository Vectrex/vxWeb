<script setup>
  import { formatFilesize } from '@/util/format-filesize';
</script>
<template>
  <div class="columns">
    <div class="column">
      <img :src="fileInfo.thumb" v-if="fileInfo.thumb" class="img-responsive">
    </div>
    <div class="column">
      <table class="table">
        <tr>
          <td>Typ</td>
          <td>{{ fileInfo.mimetype }}</td>
        </tr>
        <tr v-if="fileInfo.cache">
          <td>Cache</td>
          <td>{{ fileInfo.cache.count }} Files, {{ formatFilesize(fileInfo.cache.totalSize, ',') }}</td>
        </tr>
        <tr>
          <td>Link</td>
          <td><a :href="'/' + fileInfo.path" target="_blank">{{ fileInfo.name }}</a></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="divider" data-content="Metadaten der Datei"></div>
  <div class="form-group">
    <label for="title_input">Titel</label>
    <input id="title_input" class="form-input" v-model="form.title" autocomplete="off"
           :class="{'is-error': errors.title}">
  </div>
  <div class="form-group">
    <label for="subtitle_input">Untertitel</label>
    <input id="subtitle_input" class="form-input" v-model="form.subtitle" autocomplete="off"
           :class="{'is-error': errors.subtitle}">
  </div>
  <div class="form-group">
    <label for="description_input">Beschreibung</label>
    <textarea rows="2" id="description_input" class="form-input" v-model="form.description"
              :class="{'is-error': errors.description}"></textarea>
  </div>
  <div class="divider" data-content="Erweiterte Einstellungen"></div>
  <div class="form-group">
    <label for="customsort_input">Sortierziffer</label>
    <input id="customsort_input" class="form-input col-4" v-model="form.customsort" autocomplete="off"
           :class="{'is-error': errors.customsort}">
  </div>
  <div class="form-group">
    <button type='button' @click="submit" class='btn btn-success col-12' :class="{'loading': loading}"
            :disabled="loading">Änderungen speichern
    </button>
  </div>
</template>

<script>
export default {
  name: 'FileEditForm',
  emits: ['response-received'],
  props: {
    initialData: { type: Object, default: {} },
    fileInfo: { type: Object, default: {} },
    url: { type: String, default: "" }
  },
  data () {
    return {
      form: {},
      response: {},
      loading: false
    }
  },
  computed: {
    errors() {
      return this.response ? (this.response.errors || {}) : {};
    },
    message() {
      return this.response ? this.response.message : "";
    }
  },
  watch: {
    initialData(newValue) {
      this.form = Object.assign({}, this.form, newValue);
    }
  },
  methods: {
    async submit() {
      this.loading = true;

      /* avoid strings "null" with null values */

      let formData = {};

      Object.keys(this.form).forEach(key => {
        if (this.form[key] !== null) {
          formData[key] = this.form[key];
        }
      });

      this.response = this.$fetch(this.url, 'POST', {}, JSON.stringify(formData));
      this.$emit('response-received', this.response);
      this.loading = false;
    },
    formatFilesize: formatFilesize
  }
}
</script>