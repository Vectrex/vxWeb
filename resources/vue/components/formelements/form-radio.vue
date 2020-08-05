<template>
  <label class="form-radio" v-for="option in options" v-bind="$attrs">
    <input
        @change="$emit('input', $event.target.value)"
        :checked="value"
        :value="option.key || option.label || option"
        type="radio"
    >
    <i class="form-icon"></i> {{ option.label || option }}
  </label>
</template>

<script>
export default {
  props: {options: Array, value: [String, Number]}
}
</script>