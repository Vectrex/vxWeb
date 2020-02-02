
    import SimpleTreeBranch from './simple-tree-branch';
    import { Bubble } from "../directives";

    Vue.directive('bubble', Bubble);

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
