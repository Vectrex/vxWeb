<template>
    <div class="slick-sort-item">
        <div v-handle class="handle"></div>
        <slot name="row" :item="item">{{ item }}</slot>
    </div>
</template>

<script>
    import { ElementMixin } from 'vue-slicksort';

    export default {
        mixins: [ElementMixin],
        props: ['item']
    }
</script>