<template>
    <input
        @input="$emit('input', $event.target.value)"
        v-bind="$attrs"
        class="form-input"
    >
</template>