<template>
  <ul class="vx-tree">
    <simple-tree-branch :branch="branch" />
  </ul>
</template>

<script>
    import SimpleTreeBranch from './simple-tree-branch';

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
