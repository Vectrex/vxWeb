<template>
    <input
        v-bind="$attrs"
        class="form-input"
        v-on="{
          ...$listeners,
          input: event => $emit('input', event.target.value)
        }"
    >
</template>