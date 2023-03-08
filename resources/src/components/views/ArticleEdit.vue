<script setup>
  import Tabs from "@/components/vx-vue/tabs.vue";
  import ArticleForm from "@/components/views/articles/ArticleForm.vue";
  import ArticleFiles from "@/components/views/articles/ArticleFiles.vue";
</script>

<template>
  <div class="mb-4">
    <tabs :items="tabs.items" v-model:active-index="tabs.activeIndex" />
  </div>
  <div v-show="tabs.activeIndex === 0">
    <article-form  />
  </div>
  <div v-show="tabs.activeIndex === 1">
    <article-files />
  </div>
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
          { name: 'Verlinkte Dateien',badge: 10 },
          { name: 'Sortierung und Sichtbarkeit verlinkter Dateien' },
        ]
      },
      form: {}
    }
  },
}
</script>
