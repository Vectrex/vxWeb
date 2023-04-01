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
    <revision-table
        :revisions="revisions" class="w-1/3"
        @activate-revision="activateRevision"
        @delete-revision="deleteRevision"
        @load-revision="loadRevision"
    />
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
      this.handleResponse(await this.$fetch(this.api + 'page/' + this.id));
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
      if (response.id && !this.id) {
        this.$router.replace( { name: 'pageEdit', params: { id: response.id } });
      }
      this.$emit('notify', response);
    },
    async activateRevision (revision) {
      const response = await this.$fetch(this.api + 'revision/' + revision.id + '/activate', 'PUT');
      if (response.success) {
        this.handleResponse(response);
      }
      this.$emit('notify', response);
    },
    async deleteRevision (revision) {
      const response = await this.$fetch(this.api + 'revision/' + revision.id, 'DELETE');
      if (response.success) {
        this.handleResponse(response);
      }
      this.$emit('notify', response);
    },
    async loadRevision (revision) {

    },
    handleResponse (response) {
        this.form = response.current;
        this.revisions = (response.revisions || []).map(item => {
          item.firstCreated = new Date(item.firstCreated);
          return item;
        });
      }
    }
}
</script>
