<template>
    <span class="flex items-center">
        <button
            v-for="(breadcrumb, ndx) in items"
            :class="[
              'border-t-2 border-b-2 border-l border-r px-4 py-2 border-vxvue-500 text-vxvue-500',
              {
                'rounded-l !border-l-2': ndx === 0,
                'rounded-r !border-r-2': ndx === items.length -1,
                'bg-vxvue-500 !text-white font-bold': breadcrumb.id === currentFolder
              }
            ]"
            :key="ndx"
            @click="$emit('breadcrumb-clicked', breadcrumb.id)">{{ breadcrumb.name }}
        </button>
    </span>
</template>

<script>
export default {
  name: 'FilemanagerBreadcrumbs',
  emits: ['breadcrumb-clicked'],
  data() {
    return {
      items: []
    }
  },
  props: {
    breadcrumbs: Array,
    folders: Array,
    currentFolder: Number
  },
  watch: {
    breadcrumbs (newValue) {
      if (
          newValue.length >= this.items.length ||
          this.items.map(item => item.id).join().indexOf(newValue.map(item => item.id).join()) !== 0
      ) {
        this.items = newValue;
      }
    },
    folders: {
      deep: true,
      handler(newValue) {

        // find current folder

        let current = this.items.findIndex(item => item.id === this.currentFolder);

        if (this.items[current + 1]) {
          let ndx = newValue.findIndex(({id}) => id === this.items[current + 1].id);

          // check for deletion

          if (ndx === -1) {
            this.items = this.items.slice(0, current + 1);
          }

          // handle possible rename

          else {
            this.items[current + 1].name = newValue[ndx].name;
          }
        }
      }
    }
  }
}
</script>