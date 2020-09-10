<template>
    <textarea
        @input="$emit('input', $event.target.value)"
        v-bind="$attrs"
        :value="value"
        class="form-input"
    />
</template>

<script>
    export default {
        props: { value: String }
    }
</script>