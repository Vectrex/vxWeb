<template>
  <label class="form-switch">
    <input value="1" type="checkbox" @change="$emit('input', $event.target.checked)" v-bind="$attrs" :checked="value">
    <i class="form-icon"></i>
  </label>
</template>
<script>
  export default {
    inheritAttrs: false,
    props: ['value']
  }
</script>