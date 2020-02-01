
    import SimpleTreeBranch from './simple-tree-branch.js';

    export default {
		template: '<ul class="vx-tree"><simple-tree-branch :branch="branch" v-bubble.branch-selected=""></simple-tree-branch></ul>',
        name: 'simple-tree',
        props: {
            branch: Object
        },
        components: {
          SimpleTreeBranch: SimpleTreeBranch
        }
    };
