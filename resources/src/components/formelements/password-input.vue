<script setup>
  import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';
</script>
<template>
  <div class="relative inline-block" :class="$attrs['class']">
    <input
        :value="modelValue"
        :type="show ? 'text': 'password'"
        @input="$emit('update:modelValue', $event.target.value)"
        v-bind="inputAttrs"
        class="form-input w-full pr-10 block focus:border-brand"
    >
    <button type="button" @click.stop="show = !show" class="absolute right-0 pr-3 flex items-center inset-y-0 text-brand-700 hover:text-brand">
      <eye-slash-icon class="h-5 w-5" v-if="show" />
      <eye-icon class="h-5 w-5" v-else />
    </button>
  </div>
</template>

<script>
export default {
  name: 'password-input',
  inheritAttrs: false,
  computed: {
    inputAttrs() {
      let attrs = Object.assign({}, this.$attrs);
      delete attrs['class'];
      return attrs;
    }
  },
  props: ['modelValue'],
  emits: ['update:modelValue'],
  data () { return {
    show: false
  }}
}
</script>
