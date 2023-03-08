<script setup>
  import DatePicker from "@/components/vx-vue/datepicker.vue";
  import FormSelect from "@/components/vx-vue/form-select.vue";
</script>

<template>
  <div class="space-y-2">
    <div class="flex space-x-2 items-center" v-for="element in elements" :key="element.model">
      <label class="w-48" :for="element.model + '_' + element.type" :class="{ required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
      <component
        :is="element.type"
        :id="element.model"
        :options="element.options"
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
  components: { datepicker: DatePicker },
  name: "ArticleForm",
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
      form: {},
      errors: {},
      elements: [
        { type: DatePicker, model: 'article_date', label: 'Artikeldatum', attrs: datepickerAttrs },
        { type: DatePicker, model: 'display_from', label: 'Anzeige von', attrs: { ...datepickerAttrs, validFrom: new Date() }},
        { type: DatePicker, model: 'display_until', label: 'Anzeige bis', attrs: {...datepickerAttrs, validFrom: new Date()}},
        { type: FormSelect, model: 'articlecategoriesid', label: 'Kategorie', required: true, options: [], attrs: { 'class': 'w-64' } },

      ]
    }
  }
}
</script>
