<script setup>
  import DatePicker from "@/components/vx-vue/datepicker.vue";
  import FormSelect from "@/components/vx-vue/form-select.vue";
  import FormSwitch from "@/components/vx-vue/form-switch.vue";
  import Tiptap from "@/components/misc/tiptap.vue";
  import SubmitButton from "@/components/app/SubmitButton.vue";
  import DateFunctions from "@/util/date-functions";
</script>

<template>
  <div class="max-w-4xl py-4">
    <div class="space-y-2">
      <div class="flex items-center flex-wrap" v-for="element in elements" :key="element.model">
        <label :for="element.model" :class="{ required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
        <input
          v-if="['text', 'number'].indexOf(element.type) !== -1"
          :id="element.model"
          v-model="form[element.model]"
          :type="element.type"
          class="form-input w-full"
          v-bind="element.attrs"
        />
        <textarea
            v-else-if="element.type === 'textarea'"
            :id="element.model"
            v-model="form[element.model]"
            class="form-textarea w-full"
        />
        <component
          v-else
          :is="element.type"
          :id="element.model"
          :options="options[element.model] || []"
          v-model="form[element.model]"
          v-bind="element.attrs"
        />
      </div>

      <submit-button :busy="busy" @submit="submit">Änderungen speichern</submit-button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ArticleForm",
  components: { datepicker: DatePicker, formSelect: FormSelect, formSwitch: FormSwitch, tiptap: Tiptap },
  inject: ['api'],
  emits: ['response-received'],
  props: ['id'],
  data() {
    let datepickerAttrs = {
      placeholder: 'dd.mm.yyyy',
          'class': "w-96 w-full",
          dayNames: 'So Mo Di Mi Do Fr Sa'.split(' '),
          startOfWeekIndex: 1,
          monthNames: 'Jänner,Februar,März,April,Mai,Juni,Juli,August,September,Oktober,November,Dezember'.split(','),
          inputFormat: 'd.m.y',
          outputFormat: 'd mmm y'
    };
    return {
      busy: false,
      options: {
        articlecategoriesid: []
      },
      form: {},
      errors: {},
      elements: [
        { type: 'datepicker', model: 'article_date', label: 'Artikeldatum', attrs: datepickerAttrs },
        { type: 'datepicker', model: 'display_from', label: 'Anzeige von', attrs: { ...datepickerAttrs, validFrom: new Date() }},
        { type: 'datepicker', model: 'display_until', label: 'Anzeige bis', attrs: {...datepickerAttrs, validFrom: new Date()}},
        { type: 'form-switch', model: 'customflags', label: 'Markiert', attrs: { 'class': 'ml-2' } },
        { type: 'form-select', model: 'articlecategoriesid', label: 'Kategorie', required: true, options: this.categories, attrs: { 'class': 'w-full' } },
        { type: 'text', model: 'headline', label: 'Überschrift/Titel', required: true },
        { type: 'text', model: 'subline', label: 'Unterüberschrift' },
        { type: 'textarea', model: 'teaser', label: 'Anrisstext' },
        { type: 'tiptap', model: 'content', label: 'Inhalt', required: true, attrs: { 'class': 'w-full' } },
      ]
    }
  },
  async created () {
    this.options.articlecategoriesid = await this.$fetch(this.api +'article/categories');

    if (this.id) {
      let form = (await this.$fetch(this.api + 'article/' + this.id));
      this.elements.forEach(item => {
        if(item.type === 'datepicker' && form[item.model]) {
          form[item.model] = new Date(form[item.model]);
        }
      });
      this.form = form;
      this.form.id = this.id;
    }
  },
  methods: {
    async submit () {

      let form = {};
      Object.keys(this.form).forEach(key => {
        if (this.form[key] instanceof Date) {
          form[key] = DateFunctions.formatDate(this.form[key],'y-mm-dd');
        }
        else {
          form[key] = this.form[key];
        }
      });

      this.busy = true;
      let response = await this.$fetch(this.api + 'article/' + (this.form.id || ''), this.form.id ? 'PUT' : 'POST', {}, JSON.stringify(form));
      this.busy = false;

      this.errors = response.errors || {};
      this.$emit('response-received', { success: response.success, message: response.message });

      if (!this.form.id) {
        this.form.id = response.id;
        this.$router.replace({ name: 'articleEdit', params: { id: response.id }});
      }
    }
  }
}
</script>
