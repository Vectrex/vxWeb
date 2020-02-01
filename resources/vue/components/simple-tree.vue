<template>
  <ul class="vx-tree">
    <simple-tree-branch :branch="branch" v-bubble.branch-selected />
  </ul>
</template>

<script>
    import SimpleTreeBranch from './simple-tree-branch';
    import { Bubble } from "../directives";

    Vue.directive('bubble', Bubble);

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
