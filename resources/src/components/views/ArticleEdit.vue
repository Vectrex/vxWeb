<script setup>
  import Tabs from "@/components/vx-vue/tabs.vue";
  import ArticleForm from "@/components/views/articles/ArticleForm.vue";
  import ArticleFiles from "@/components/views/articles/ArticleFiles.vue";
</script>

<template>
  <div class="mb-4">
    <tabs :items="tabs.items" v-model:active-index="tabs.activeIndex" />
  </div>
  <div v-if="tabs.activeIndex === 0">
    <article-form :id="id" />
  </div>
  <div v-if="tabs.activeIndex === 1">
    <article-files />
  </div>
</template>

<script>
export default {
  name: "ArticleEdit",
  props: ['id'],
  inject: ['api'],
  data () {
    return {
      tabs: {
        activeIndex: 0,
        items: [
          { name: 'Artikel' },
          { name: 'Verlinkte Dateien', badge: 10 },
          { name: 'Sortierung und Sichtbarkeit verlinkter Dateien' },
        ]
      }
    }
  },
  async created () {
    if (this.id) {
      const response = await this.$fetch(this.api + 'article/' + this.id + '/linked-files');
      this.tabs.items[1].badge = response.files?.length || 0;
    }
  }
}
</script>
