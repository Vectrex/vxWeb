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
    <page-form :init-data="form" class="w-full" @response-received="handleFormResponse($event)"/>
    <div class="w-1/3 flex-shrink-0 overflow-hidden h-[calc(100vh-var(--header-height)-1.5rem)]">
      <revision-table
          :revisions="revisions" class="w-full h-full overflow-y-auto"
          @activate-revision="activateRevision"
          @delete-revision="deleteRevision"
          @load-revision="loadRevision"
      />
    </div>
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
    handleFormResponse(response) {
      if (response.revisions) {
        this.revisions = response.revisions.map(item => {
          item.firstCreated = new Date(item.firstCreated);
          return item;
        });
      }
      if (response.id && !this.id) {
        this.$router.replace({name: 'pageEdit', params: {id: response.id}});
      }
      this.$emit('notify', response);
    },
    async activateRevision(revision) {
      const response = await this.$fetch(this.api + 'revision/' + revision.id + '/activate', 'PUT');
      if (response.success) {
        let active = this.revisions.find(item => item.active === true);
        if (active) {
          active.active = false;
        }
        active = this.revisions.find(item => item === revision);
        if (active) {
          active.active = true;
        }
        this.handleResponse(response);
      }
      this.$emit('notify', response);
    },
    async deleteRevision(revision) {
      const response = await this.$fetch(this.api + 'revision/' + revision.id, 'DELETE');
      if (response.success) {
        this.handleResponse(response);
      }
      this.$emit('notify', response);
    },
    async loadRevision(revision) {
      const response = await this.$fetch(this.api + 'revision/' + revision.id);
      if (response.success) {
        this.handleResponse(response);
      }
    },
    handleResponse(response) {
      if (response.current) {
        this.form = response.current;
      }
      if (response.revisions) {
        this.revisions = response.revisions.map(item => {
          item.firstCreated = new Date(item.firstCreated);
          return item;
        });
      }
    }
  }
}
</script>
