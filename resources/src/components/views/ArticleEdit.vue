<script setup>
  import Tabs from "@/components/vx-vue/tabs.vue";
  import Headline from "@/components/app/Headline.vue";
  import ArticleForm from "@/components/views/articles/ArticleForm.vue";
  import ArticleFiles from "@/components/views/articles/ArticleFiles.vue";
  import LinkedFiles from "@/components/views/articles/LinkedFiles.vue";
</script>

<template>
  <teleport to="#tools">
    <headline><span>Artikel {{ id ? 'bearbeiten' : 'anlegen' }}</span></headline>
  </teleport>

  <div class="mb-4">
    <tabs :items="tabs.items" v-model:active-index="tabs.activeIndex" />
  </div>
  <div v-if="tabs.activeIndex === 0">
    <article-form :id="id" @response-received="$emit('notify', $event)" />
  </div>
  <div v-if="tabs.activeIndex === 1">
    <article-files />
  </div>
  <div v-if="tabs.activeIndex === 2 && id">
    <linked-files :article-id="id" />
  </div>
</template>

<script>
export default {
  name: "ArticleEdit",
  props: ['id'],
  inject: ['api'],
  emits: ['notify'],
  data () {
    return {
      tabs: {
        activeIndex: 0,
        items: [
          { name: 'Artikel' },
          { name: 'Verlinkte Dateien', badge: null, disabled: this.disableTabs },
          { name: 'Sortierung und Sichtbarkeit verlinkter Dateien', disabled: this.disableTabs },
        ]
      }
    }
  },
  computed: {
    disableTabs () {
      return !this.id;
    }
  },
  async created () {
    if (this.id) {
      const response = await this.$fetch(this.api + 'article/' + this.id + '/linked-files');
      this.tabs.items[1].badge = response.length || 0;
    }
  }
}
</script>
