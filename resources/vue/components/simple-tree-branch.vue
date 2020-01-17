<template>
  <li :class="{ 'terminates': !branch.branches || !branch.branches.length, 'current': branch.current }">
    <template v-if="branch.branches && branch.branches.length">
      <input type="checkbox" :id="branch.key" @click="expanded = !expanded">
      <label :for="branch.key"></label>
    </template>
    <span  :class="{ 'text-bold': branch.current }">{{ branch.label }}</span>
    <ul v-if="branch.branches && branch.branches.length" v-show="expanded">
      <simple-tree-branch v-for="child in branch.branches" :branch="child"></simple-tree-branch>
    </ul>
  </li>
</template>

<script>
    export default {
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
</script>
