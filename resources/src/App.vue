<script setup>
  import { computed } from "vue";
</script>
<template>
  <router-view @notify="notify" @authenticate="authenticate" class="pt-28"/>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      auth: {},
      toast: {}
    }
  },
  provide () {
    return {
      auth: computed(() => this.auth)
    }
  },
  created () {
  },
  methods: {
    authenticate (event) {
      this.auth = event;
    },
    notify (data) {
      this.toast = {
        active: true,
        message: data.message || (data.success ? 'Success!' : 'Failure!'),
        css: data.success ? 'bg-green-800 text-white' : 'bg-red-800 text-white'
      }
    }
  }

}
</script>
