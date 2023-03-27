<script setup>
  import Tabs from "@/components/vx-vue/tabs.vue";
  import Headline from "@/components/app/Headline.vue";
</script>

<template>
  <teleport to="#tools">
    <headline><span>Seite {{ id ? 'bearbeiten' : 'anlegen' }}</span></headline>
  </teleport>
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
