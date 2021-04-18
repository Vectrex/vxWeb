<template>
    <input
        v-bind="$attrs"
        class="form-input"
        @input="event => $emit('input', event.target.value)"
    >
</template>