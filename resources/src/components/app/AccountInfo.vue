<script setup>
  import { PowerIcon, Cog6ToothIcon } from '@heroicons/vue/24/solid';
</script>
<template>
  <div class="space-y-2">
    <router-link
      :to="{ name: 'profile' }"
      class="text-white hover:text-white/75 flex space-x-2 items-center"
    >
      <cog-6-tooth-icon class="h-5 w-5" /><span>{{ user.username }} ({{ user.email }})</span>
    </router-link>
    <a href="#" @click.prevent="$emit('authenticate', null)"
       class="text-white hover:text-white/75 flex space-x-2 items-center"
    ><power-icon class="h-5 w-5" /><span>Abmelden</span></a>
  </div>
</template>

<script>
export default {
  name: "AccountInfo",
  emits: ['authenticate'],
  props: { user: Object }
}
</script>
