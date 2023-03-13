<script setup>
  import DatePicker from "@/components/vx-vue/datepicker.vue";
  import FormSelect from "@/components/vx-vue/form-select.vue";
  import SubmitButton from "@/components/app/SubmitButton.vue";
  import tiptap from "@/components/misc/tiptap.vue";
  import Tiptap from "@/components/misc/tiptap.vue";
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
        <input
            v-else-if="element.type === 'checkbox'"
            :id="element.model"
            v-model="form[element.model]"
            :type="element.type"
            class="form-checkbox ml-2"
            v-bind="element.attrs"
            :true-value="true"
            :false-value="false"
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
      <tiptap v-model="form.content" />

      <submit-button :busy="busy" @submit="submit">Änderungen speichern</submit-button>
    </div>
  </div>
</template>

<script>
export default {
  components: { datepicker: DatePicker, formSelect: FormSelect },
  name: "ArticleForm",
  inject: ['api'],
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
        { type: 'checkbox', model: 'customflags', label: 'Markiert' },
        { type: 'form-select', model: 'articlecategoriesid', label: 'Kategorie', required: true, options: this.categories, attrs: { 'class': 'w-full' } },
        { type: 'text', model: 'headline', label: 'Überschrift/Titel', required: true },
        { type: 'text', model: 'subline', label: 'Unterüberschrift' },
        { type: 'textarea', model: 'teaser', label: 'Anrisstext' },
        { type: 'textarea', model: 'content', label: 'Inhalt', required: true },
      ]
    }
  },
  async created () {
    this.options.articlecategoriesid = await this.$fetch(this.api +'article/categories');

    if (this.id) {
      let form = (await this.$fetch(this.api + 'article/' + this.id)).form;
      this.elements.forEach(item => {
        if(item.type === 'datepicker' && form[item.model]) {
          form[item.model] = new Date(form[item.model]);
        }
      });
      this.form = form;
    }
  },
  methods: {
    async submit () {
      this.busy = true;
    }
  }
}
</script>
