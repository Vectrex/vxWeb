<template>
  <ul class="vx-tree">
    <simple-tree-branch :branch="branch" v-bubble.branch-selected />
  </ul>
</template>

<script>
    import SimpleTreeBranch from './simple-tree-branch.js';

    export default {
        name: 'simple-tree',
        props: {
            branch: Object
        },
        components: {
          SimpleTreeBranch: SimpleTreeBranch
        }
    };
</script>
