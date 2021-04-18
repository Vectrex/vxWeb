<template>
    <input
        v-bind="$attrs"
        :value="modelValue"
        class="form-input"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
<script>
  export default {
    props: ['modelValue'],
    emits: ['update:modelValue']
  }
</script>