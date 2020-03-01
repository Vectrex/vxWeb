<template>
    <div
        :use-drag-handle="true"
    >
        <filesort-item v-for="(item, ndx) in value" :key="ndx" :index="ndx" :item="item">
            <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData"><slot :name="name" v-bind="slotData" /></template>
        </filesort-item>
    </div>

</template>

<script>
    import { ContainerMixin } from 'vue-slicksort';
    import FilesortItem from './filesort-item';

    export default {
        mixins: [ContainerMixin],
        components: { FilesortItem },
        props: {
            value: {
                type: Array,
                default: []
            }
        }
    }
</script>