<script setup>
  import Headline from "@/components/app/Headline.vue";
  import PageForm from "@/components/views/pages/PageForm.vue";
  import RevisionTable from "@/components/views/pages/RevisionTable.vue";
</script>

<template>
  <teleport to="#tools">
    <headline><span>Seite {{ id ? 'bearbeiten' : 'anlegen' }}</span></headline>
  </teleport>
  <div class="flex w-full space-x-4 justify-start">
    <page-form :init-data="form" class="w-2/3" @response-received="handleFormResponse($event)"/>
    <revision-table :revisions="revisions" class="w-1/3" />
  </div>
</template>

<script>
export default {
  name: "PageEdit",
  props: ['id'],
  inject: ['api'],
  emits: ['notify'],
  data () {
    return {
      form: {},
      revisions: []
    }
  },
  async created () {
    if (this.id) {
      const response = await this.$fetch(this.api + 'page/' + this.id);
      this.form = response.current;
      this.revisions = (response.revisions || []).map(item => {
        item.firstCreated = new Date(item.firstCreated);
        return item;
      });
    }
  },
  methods: {
    handleFormResponse (response) {
      if (response.revisions) {
        this.revisions = response.revisions.map(item => {
          item.firstCreated = new Date(item.firstCreated);
          return item;
        });
      }
      this.$emit('notify', response);
    }
  }
}
</script>
