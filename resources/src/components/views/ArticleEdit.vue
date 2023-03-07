<script setup>
  import Tabs from "@/components/vx-vue/tabs.vue";
  import DatePicker from "@/components/vx-vue/datepicker.vue";
</script>

<template>
  <tabs :items="tabs.items" v-model:active-index="tabs.activeIndex" />
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
  name: "ArticleEdit",
  props: ['id'],
  data () {
    return {
      tabs: {
        activeIndex: null,
        items: [
          { name: 'Artikel' },
          { name: 'Verlinkte Dateien' },
          { name: 'Sortierung und Sichtbarkeit verlinkter Dateien' },
        ]
      },
      form: {}
    }
  },
}
</script>
