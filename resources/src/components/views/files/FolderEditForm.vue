<template>
  <div>{{ folderInfo.path }}</div>
  <div class="divider" data-content="Metadaten des Ordners"></div>
  <div class="form-group">
    <label for="title_input">Titel</label>
    <input id="title_input" class="form-input" v-model="form.title" autocomplete="off" :class="{'is-error': errors.title}">
  </div>
  <div class="form-group">
    <label for="description_input">Beschreibung</label>
    <textarea rows="2" id="description_input" class="form-input" v-model="form.description" :class="{'is-error': errors.description}"></textarea>
  </div>
  <div class="form-group">
    <button type='button' @click="submit" class='btn btn-success col-12' :class="{'loading': loading}"
            :disabled="loading">Änderungen speichern
    </button>
  </div>
</template>

<script>
export default {
  name: 'FolderEditForm',
  emits: ['response-received'],
  props: {
    initialData: { type: Object, default: {} },
    folderInfo: { type: Object, default: {} },
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

      this.response = await this.$fetch(this.url, 'POST', {}, JSON.stringify(formData));
      this.$emit('response-received', this.response);
      this.loading = false;
    }
  }
}
</script>