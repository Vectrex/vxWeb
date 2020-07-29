<template>
    <input
        :value="value"
        @input="$emit('input', $event.target.value)" :type="show ? 'text': 'password'"
        v-bind="$attrs"
    >
</template>

<script>
    export default {
        props: { value: String }
    }
</script>