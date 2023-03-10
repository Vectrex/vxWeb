<script setup>
  import DatePicker from "@/components/vx-vue/datepicker.vue";
  import FormSelect from "@/components/vx-vue/form-select.vue";
  import spinner from "@/components/misc/spinner.vue";
</script>

<template>
  <div class="space-y-2">
    <div class="flex space-x-2 items-center" v-for="element in elements" :key="element.model">
      <label class="w-48" :for="element.model" :class="{ required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
      <input
        v-if="['text', 'number'].indexOf(element.type) !== -1"
        :id="element.model"
        v-model="form[element.model]"
        :type="element.type"
        class="form-input"
        v-bind="element.attrs"
      />
      <input
          v-else-if="element.type === 'checkbox'"
          :id="element.model"
          v-model="form[element.model]"
          :type="element.type"
          class="form-checkbox"
          v-bind="element.attrs"
          :true-value="true"
          :false-value="false"
      />
      <textarea
          v-else-if="element.type === 'textarea'"
          :id="element.model"
          v-model="form[element.model]"
          class="form-textarea"
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

    <div class="flex space-x-2 items-center">
      <button class="button success" type="button" @click="submit" :disabled="busy">
        Änderungen speichern
      </button>
      <spinner v-if="busy" class="text-green-700" />
    </div>
  </div>
</template>

<script>
export default {
  components: { datepicker: DatePicker, formSelect: FormSelect },
  name: "ArticleForm",
  inject: ['api'],
  data() {
    let datepickerAttrs = {
      placeholder: 'dd.mm.yyyy',
          'class': "w-64 inline-block",
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
        { type: 'form-select', model: 'articlecategoriesid', label: 'Kategorie', required: true, options: this.categories, attrs: { 'class': 'w-64' } },
        { type: 'text', model: 'title', label: 'Überschrift/Titel', required: true },
        { type: 'text', model: 'subline', label: 'Unterüberschrift' },
        { type: 'textarea', model: 'teaser', label: 'Anrisstext' },
      ]
    }
  },
  async created () {
    this.options.articlecategoriesid = await this.$fetch(this.api +'article/categories');
  }
}
</script>
