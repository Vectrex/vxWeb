<template>
  <div v-bind="$attrs">
    <label class="form-radio" v-for="option in options">
      <input
          @change="$emit('input', $event.target.value)"
          :checked="(option.key || option.label || option) == value"
          :value="option.key || option.label || option"
          type="radio"
      >
      <i class="form-icon"></i> {{ option.label || option }}
    </label>
  </div>
</template>

<script>
export default {
  props: { value: [String, Number], options: Array }
}
</script>