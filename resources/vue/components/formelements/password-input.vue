<template>
    <div class="form-group is-password" :class="{'show': show }">
        <input
            :value="value"
            @input="$emit('input', $event.target.value)" :type="show ? 'text': 'password'"
            class="form-input input-lg"
            v-bind="$attrs"
        >
        <a class="visibility-switch" href="#" @click.prevent="show = !show"></a>
    </div>
</template>

<script>
    export default {
        data () { return {
            show: false
        }},
        props: { value: String },
        inheritAttrs: false
    }
</script>