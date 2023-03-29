<script setup>
  import { PowerIcon, Cog6ToothIcon } from '@heroicons/vue/24/solid';
</script>
<template>
  <div class="space-y-2">
    <router-link
      :to="{ name: 'profile' }"
      class="text-white hover:text-white/75 flex space-x-2 items-center overflow-hidden flex-nowrap"
    >
      <cog-6-tooth-icon class="h-8 w-8 flex-shrink-0" /><span class="whitespace-nowrap" v-if="expanded">{{ user.username }} ({{ user.email }})</span>
    </router-link>
    <a href="logout" @click.prevent="$emit('authenticate', null)"
       class="text-white hover:text-white/75 flex space-x-2 items-center"
    >
      <power-icon class="h-8 w-8 flex-shrink-0" /><span v-if="expanded">Abmelden</span></a>
  </div>
</template>

<script>
export default {
  name: "AccountInfo",
  emits: ['authenticate'],
  props: { user: Object, expanded: Boolean }
}
</script>
