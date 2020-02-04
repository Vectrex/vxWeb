
    export default {
		template: '<li :class="{ &#39;terminates&#39;: !branch.branches || !branch.branches.length }"><template v-if="branch.branches &amp;&amp; branch.branches.length"><input type="checkbox" :id="&#39;branch-&#39; + branch.key" @click="expanded = !expanded" :checked="expanded"><label :for="&#39;branch-&#39; + branch.key"></label></template><strong v-if="branch.current">{{ branch.label }}</strong><a :href="branch.path" @click.prevent="$emit(&#39;branch-selected&#39;, branch)" v-else="">{{ branch.label }}</a><ul v-if="branch.branches &amp;&amp; branch.branches.length" v-show="expanded"><simple-tree-branch v-for="child in branch.branches" :branch="child" v-bubble.branch-selected="" :key="child.key"></simple-tree-branch></ul></li>',
      name: 'simple-tree-branch',

      data () { return {
        expanded: false
      }},

      props: {
        branch: { type: Object, default: () => { return {} } }
      },

      mounted () {
        if(this.branch.current) {
          let parent = this.$parent;
          while(parent && parent.branch && parent.expanded !== undefined) {
            parent.expanded = true;
            parent = parent.$parent;
          }
        }
      }
    };
