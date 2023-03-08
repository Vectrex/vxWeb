<script setup>
  import DatePicker from "@/components/vx-vue/datepicker.vue";
</script>

<template>
  <date-picker
      placeholder="dd.mm.yyyy"
      class="w-2/3 inline-block"
      id="date-picker-2"
      v-model="form.article_date"
      :day-names="'So Mo Di Mi Do Fr Sa'.split(' ')"
      :start-of-week-index="1"
      :month-names="'Jänner,Februar,März,April,Mai,Juni,Juli,August,September,Oktober,November,Dezember'.split(',')"
      input-format="d.m.y"
      output-format="d mmm y"
  />
</template>

<script>
export default {
  name: "ArticleForm",
  data() {
    return {
      form: {}
    }
  }
}
</script>
