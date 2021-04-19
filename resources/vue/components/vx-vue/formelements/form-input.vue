<template>
    <input
        :value="modelValue"
        class="form-input"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
<script>
  export default {
    name: 'form-input',
    props: ['modelValue'],
    emits: ['update:modelValue']
  }
</script>