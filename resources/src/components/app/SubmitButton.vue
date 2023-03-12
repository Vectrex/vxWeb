<script setup>
  import Spinner from "@/components/misc/spinner.vue";
</script>

<template>
  <div class="flex space-x-2 items-center">
    <button class="button success" type="button" @click="$emit('submit')" :disabled="busy"><slot /></button>
    <spinner v-if="busy" class="!text-green-700" />
  </div>
</template>

<script>
export default {
  name: "SubmitButton",
  emits: ['submit'],
  props: { busy: Boolean }
}
</script>