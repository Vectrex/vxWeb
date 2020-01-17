
    export default {
		template: '<li :class="{ &#39;terminates&#39;: !branch.branches || !branch.branches.length, &#39;current&#39;: branch.current }"><template v-if="branch.branches &amp;&amp; branch.branches.length"><input type="checkbox" :id="branch.key" @click="expanded = !expanded"><label :for="branch.key"></label></template><span :class="{ &#39;text-bold&#39;: branch.current }">{{ branch.label }}</span><ul v-if="branch.branches &amp;&amp; branch.branches.length" v-show="expanded"><simple-tree-branch v-for="child in branch.branches" :branch="child"></simple-tree-branch></ul></li>',
      name: 'simple-tree-branch',
      data () { return {
        expanded: false
      }},
      props: {
        branch: Object
      },
      methods: {
      }
    };
