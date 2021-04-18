<template>
    <select
        v-bind="$attrs"
        class="form-select"
        @change="event => $emit('input', event.target.value)"
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
      props: { options: Array }
    }
</script>