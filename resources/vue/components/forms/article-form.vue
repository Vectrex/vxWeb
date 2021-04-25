<template>
  <form action="/" class="form-horizontal" @submit.prevent>
    <div v-for="element in elements" class="form-group">
      <label class="form-label col-3" :for="element.model + '_' + element.type" :class=" { required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
      <div class="col-9">
        <component
            :is="element.type || 'form-input'"
            :id="element.model + '_' + element.type"
            :name="element.model"
            :options="element.options"
            v-model="form[element.model]"
            v-bind="element.attrs"
        >
        </component>
      </div>
    </div>

    <div class="divider"></div>

    <div class="form-group">
      <button type='button' @click="submit" class='btn btn-success off-3 col-3' :class="{'loading': loading}" :disabled="loading">{{ form.id ? 'Daten übernehmen' : 'Artikel anlegen' }}</button>
    </div>
  </form>
</template>
<script>

import SimpleFetch from "../../util/simple-fetch.js";
import VueCkeditor from "../vue-ckeditor";
import Datepicker from "../vx-vue/formelements/datepicker";
import FormInput from "../vx-vue/formelements/form-input";
import FormTextarea from "../vx-vue/formelements/form-textarea";
import FormSelect from "../vx-vue/formelements/form-select";
import FormCheckbox from "../vx-vue/formelements/form-checkbox";

export default {
  components: {
    'vue-ckeditor': VueCkeditor,
    'datepicker': Datepicker,
    'form-input': FormInput,
    'form-textarea': FormTextarea,
    'form-select': FormSelect,
    'form-checkbox': FormCheckbox
  },

  props: {
    url: { type: String, required: true },
    initialData: { type: Object, default: () => ({}) },
    options: Object,
    editorConfig: Object
  },

  data() { return {
      elements: [
        { type: 'datepicker', model: 'article_date', label: 'Artikeldatum', attrs: {
            'input-format': "d.m.y",
            'output-format': "d.m.y",
            'day-names': 'Mo Di Mi Do Fr Sa So'.split(' '),
            'month-names': 'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' ') }},
        { type: 'datepicker', model: 'display_from', label: 'Anzeige von', attrs: {
            'input-format': "d.m.y",
            'output-format': "d.m.y",
            'day-names': 'Mo Di Mi Do Fr Sa So'.split(' '),
            'month-names': 'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' '),
            'valid-from': new Date() }},
        { type: 'datepicker', model: 'display_until', label: 'Anzeige bis', attrs: {
            'input-format': "d.m.y",
            'output-format': "d.m.y",
            'day-names': 'Mo Di Mi Do Fr Sa So'.split(' '),
            'month-names': 'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' '),
            'valid-from': new Date() }},
        { model: 'customsort', label: 'generische Sortierung', attrs: { 'class': 'col-2', maxlength: 4 } },
        { type: 'form-checkbox', model: 'customflags', label: 'Markiert' },
        { type: 'form-select', model: 'articlecategoriesid', label: 'Kategorie', required: true, options: [] },
        { model: 'headline', label: 'Überschrift/Titel', required: true },
        { model: 'subline', label: 'Unterüberschrift' },
        { type: 'form-textarea', model: 'teaser', label: 'Anrisstext' },
        { type: 'vue-ckeditor', model: 'content', label: 'Inhalt', required: true, attrs: { config: this.editorConfig } }
      ],
      form: {},
      response: {},
      loading: false
  }},

  computed: {
    errors () {
      return this.response ? (this.response.errors || {}) : {};
    },
    message () {
      return this.response ? this.response.message : "";
    }
  },

  watch: {
    initialData (newValue) {
      this.form = Object.assign({}, this.form, newValue);
      this.setDates();
    },
    options (newValue) {
      this.elements[this.elements.findIndex(({model}) => model === 'articlecategoriesid')].options = newValue.categories;
    }
  },

  mounted () {
    this.form = Object.assign({}, this.form, this.initialData);
    this.setDates();
  },

  methods: {
    async submit () {
      this.loading = true;
      let payload = Object.assign({}, this.form);
      Object.keys(payload).forEach(prop => {
        if(payload[prop] instanceof Date) {
          payload[prop] = payload[prop].getFullYear() + '-' + (payload[prop].getMonth() + 1) + '-' + payload[prop].getDate();
        }
      });
      this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(payload));
      this.form.id = this.response.id;
      this.$emit("response-received", this.response);
      this.loading = false;
    },
    setDates () {
      ['article_date', 'display_from', 'display_until'].forEach(item => {
        if (this.form[item] && !(this.form[item] instanceof Date)) {
          this.form[item] = new Date(this.form[item]);
        }
      });
    }
  }
}
</script>