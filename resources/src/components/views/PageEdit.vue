<script setup>
  import Headline from "@/components/app/Headline.vue";
  import PageForm from "@/components/views/pages/PageForm.vue";
</script>

<template>
  <teleport to="#tools">
    <headline><span>Seite {{ id ? 'bearbeiten' : 'anlegen' }}</span></headline>
  </teleport>
  <page-form />
</template>

<script>
export default {
  name: "PageEdit",
  props: ['id'],
  inject: ['api'],
  emits: ['notify'],
  data () {
    return {
    }
  },
  computed: {
  },
  created () {
  },
  methods: {
  }
}
</script>
