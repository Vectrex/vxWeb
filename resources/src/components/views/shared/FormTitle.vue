<script setup>
import { XMarkIcon } from '@heroicons/vue/24/solid';
</script>
<template>
  <div class="fixed flex justify-between items-center bg-vxvue-500 h-16 px-4">
    <span class="text-xl font-bold text-white"><slot /></span>
    <a href="#" @click.prevent="$emit('cancel')"><x-mark-icon class="w-5 h-5 text-white"/></a>
  </div>
</template>

<script>
  export default {
    name: 'FormTitle',
    emits: ['cancel']
  }
</script>