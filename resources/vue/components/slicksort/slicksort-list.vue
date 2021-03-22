<template>
    <div
        :use-drag-handle="true"
    >
        <slicksort-item v-for="(item, ndx) in value" :key="ndx" :index="ndx" :item="item">
            <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData"><slot :name="name" v-bind="slotData" /></template>
        </slicksort-item>
    </div>

</template>

<script>
    import { ContainerMixin } from 'vue-slicksort';
    import SlicksortItem from './slicksort-item';

    export default {
        mixins: [ContainerMixin],
        components: { SlicksortItem },
        props: {
            value: {
                type: Array,
                default: []
            }
        }
    }
</script>