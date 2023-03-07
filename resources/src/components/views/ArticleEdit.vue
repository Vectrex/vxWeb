<script setup>
  import Tabs from "@/components/vx-vue/tabs.vue";
  import Datepicker from "@/components/vx-vue/datepicker.vue";
</script>

<template>
  <tabs :items="tabs.items" />
  <date-picker />
</template>

<script>
export default {
  name: "ArticleEdit",
  props: ['id'],
  data () {
    return {
      tabs: {
        items: [
          { name: 'Artikel' },
          { name: 'Verlinkte Dateien' },
          { name: 'Sortierung und Sichtbarkeit verlinkter Dateien' },
        ]
      }
    }
  },
}
</script>
