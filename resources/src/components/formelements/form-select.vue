<template>
    <select
        v-bind="$attrs"
        :value="modelValue"
        class="form-select"
        @change="emit($event.target)"
    >
      <option v-if="disabledLabel" disabled value="">{{ disabledLabel }}</option>
      <option
          v-for="option in options"
          :value="option.key !== undefined ? option.key : (option.label || option)"
          :selected="(option.key !== undefined ? option.key : (option.label || option)) === modelValue"
      >{{ option.label || option }}
      </option>
    </select>
</template>

<script>
    export default {
      name: 'form-select',
      props: { options: Array, modelValue: [String, Number], disabledLabel: String },
      emits: ['update:modelValue'],
      methods: {
        emit (target) {
          this.$emit('update:modelValue', target.value);
        }
      }
    }
</script>