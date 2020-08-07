<template>
    <select
        @change="$emit('input', $event.target.value)"
        :value="value"
        v-bind="$attrs"
        class="form-select"
    >
      <option
          v-for="option in options"
          :value="option.key || option.label || option"
          :selected="(option.key || option.label || option) == value"
      >{{ option.label || option }}
      </option>
    </select>
</template>

<script>
    export default {
      props: { options: Array, value: [String, Number] }
    }
</script>