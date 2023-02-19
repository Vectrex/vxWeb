<script setup>
  import SimpleTreeBranch from "@/components/vx-vue/simple-tree/simple-tree-branch.vue";
</script>
<template>
  <ul>
    <simple-tree-branch :branch="branch" @branch-selected="$emit('branch-selected', $event)" />
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
