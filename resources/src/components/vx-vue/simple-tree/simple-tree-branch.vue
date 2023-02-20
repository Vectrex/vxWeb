<script setup>
  import { PlusIcon, MinusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
  <li :class="{ 'terminates': !branch.branches || !branch.branches.length }">
    <div class="flex items-center space-x-2 py-1">
      <a href="" @click.prevent.stop="expanded = !expanded" v-if="branch.branches && branch.branches.length">
        <div class="p-px border-2 rounded-sm"><component :is="expanded ? MinusIcon : PlusIcon" class="h-4 w-4" /></div>
      </a>
      <strong v-if="branch.current">{{ branch.label }}</strong>
      <a :href="branch.path" @click.prevent="$emit('branch-selected', branch)" v-else>{{ branch.label }}</a>
    </div>
    <ul class="pl-2" v-if="branch.branches && branch.branches.length" v-show="expanded">
      <simple-tree-branch
          v-for="child in branch.branches"
          :branch="child"
          :key="child.id"
          @branch-selected="$emit('branch-selected', $event)"
          @expand="expanded = $event; $emit('expand', $event)"
      />
    </ul>
  </li>
</template>

<script>
export default {
  name: 'simple-tree-branch',
  emits: ['branch-selected', 'expand'],
  components: { PlusIcon, MinusIcon },
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
      this.$emit('expand', true);
    }
  }
};
</script>
