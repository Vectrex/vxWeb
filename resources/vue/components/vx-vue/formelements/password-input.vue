<template>
    <div class="form-group is-password">
      <input
        v-bind="$attrs"
        :value="modelValue"
        class="form-input"
        :type="show ? 'text': 'password'"
        @input="$emit('update:modelValue', $event.target.value)"
      >
      <a :class="{ 'show': show }" href="#" @click.prevent="show = !show"></a>
    </div>
</template>

<script>
    export default {
      inheritAttrs: false,
      props: ['modelValue'],
      emits: ['update:modelValue'],
      data () { return {
        show: false
      }}
    }
</script>

<style scoped lang="scss">
  @import "../../../../node_modules/spectre.css/src/variables";

  div {
    position: relative;

    a {
      &:focus {
        box-shadow: none;
        text-decoration: none;
        font-family: icomoon;
      }
      &:after {
        height: $control-icon-size;
        margin: 0 $control-padding-y;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: $control-icon-size;
        z-index: $zindex-0 + 1;
        line-height: 100%;
        right: $border-width;
        font-family: icomoon;
        content: '\e015';
      }
      &.show:after {
        content: '\e016';
      }
    }
  }

</style>