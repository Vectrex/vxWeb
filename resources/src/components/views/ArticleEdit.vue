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
    <tabs
      :items="disabledTabs"
      :active-index="tabs.items.findIndex(item => item.section === activeTab)"
      @update:active-index="$router.push({ name: 'articleEdit', params: { id: id, section: tabs.items[$event].section }})"
    />
  </div>
  <div v-if="activeTab === 'edit'">
    <article-form
        :id="id"
        @response-received="$emit('notify', $event)"
    />
  </div>
  <div v-if="activeTab === 'files' && id">
    <article-files
        :article-id="id"
        :selected-folder="selectedFolder"
        @update-linked="getLinkedFiles"
        @notify="$emit('notify', $event)"
    />
  </div>
  <div v-if="activeTab === 'sort' && id">
    <linked-files
        :article-id="id"
        @update-linked="getLinkedFiles"
        @goto-folder="gotoFolder"
    />
  </div>
</template>

<script>
export default {
  name: "ArticleEdit",
  props: ['id', 'sectionId'],
  inject: ['api'],
  emits: ['notify'],
  data () {
    return {
      selectedFolder: null,
      tabs: {
        items: [
          { section: 'edit', name: 'Artikel' },
          { section: 'files', name: 'Verlinkte Dateien', badge: null },
          { section: 'sort', name: 'Sortierung und Sichtbarkeit verlinkter Dateien' },
        ]
      }
    }
  },
  computed: {
    disabledTabs () {
      let items = this.tabs.items;
      items[1].disabled = items[2].disabled = !this.id;
      return items;
    },
    activeTab () {
      return this.$route.params.section || 'edit';
    }
  },
  created () {
    this.getLinkedFiles();
  },
  methods: {
    async getLinkedFiles() {
      if (this.id) {
        const response = await this.$fetch(this.api + 'article/' + this.id + '/linked-files');
        this.tabs.items[1].badge = response.length || 0;
      }
    },
    gotoFolder (folder) {
      this.selectedFolder = folder;
      this.$router.push( { name: 'articleEdit', params: { id: this.id, section: 'files', sectionId: folder.id }});
    }
  }
}
</script>
