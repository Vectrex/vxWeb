<script setup>
  import SimpleTreeBranch from "@/components/vx-vue/simple-tree/simple-tree-branch.vue";
</script>
<template>
  <ul class="flex">
    <simple-tree-branch :branch="branch" @branch-selected="$emit('branch-selected', $event)" v-bind="$attrs" />
  </ul>
</template>

<script>
    export default {
        name: 'simple-tree',
        props: {
            branch: Object
        }
    };
</script>
