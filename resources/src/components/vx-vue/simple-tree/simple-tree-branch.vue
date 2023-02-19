<script setup>
  import { FolderIcon, FolderOpenIcon } from '@heroicons/vue/24/solid';
</script>
<template>
  <li :class="{ 'terminates': !branch.branches || !branch.branches.length }">
    <div class="flex items-center space-x-2 py-1">
      <a href="" @click.prevent.stop="expanded = !expanded" v-if="branch.branches && branch.branches.length">
        <component :is="expanded ? FolderOpenIcon : FolderIcon" class="h-5 w-5" />
      </a>
      <strong v-if="branch.current">{{ branch.label }}</strong>
      <a :href="branch.path" @click.prevent="$emit('branch-selected', branch)" v-else>{{ branch.label }}</a>
    </div>
    <ul class="pl-2" v-if="branch.branches && branch.branches.length" v-show="expanded">
      <simple-tree-branch v-for="child in branch.branches" :branch="child" :key="child.id" @branch-selected="$emit('branch-selected', $event)" />
    </ul>
  </li>
</template>

<script>
export default {
  name: 'simple-tree-branch',
  emits: ['branch-selected'],
  components: { FolderIcon, FolderOpenIcon },
  data() {
    return {
      expanded: false
    }
  },

  props: {
    branch: { type: Object, default: {} }
  },

  mounted() {
    if (this.branch.current) {
      let parent = this.$parent;
      while (parent && parent.branch && parent.expanded !== undefined) {
        parent.expanded = true;
        parent = parent.$parent;
      }
    }
  }
};
</script>
