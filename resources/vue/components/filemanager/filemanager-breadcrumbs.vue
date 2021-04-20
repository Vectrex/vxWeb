<template>
    <span class="btn-group">
        <button
            v-for="(breadcrumb, ndx) in items"
            class="btn"
            :key="ndx"
            :class="{ 'active': breadcrumb.folder === currentFolder }"
            @click="$emit('breadcrumb-clicked', breadcrumb.folder)">{{ breadcrumb.name }}
        </button>
    </span>
</template>

<script>
export default {
  name: 'filemanager-breadcrumbs',
  emits: ['breadcrumb-clicked'],
  data() {
    return {items: []}
  },
  props: {
    breadcrumbs: Array,
    folders: Array,
    currentFolder: Number
  },
  watch: {
    breadcrumbs(newValue) {
      if (
          newValue.length >= this.items.length ||
          this.items.map(item => item.folder).join().indexOf(newValue.map(item => item.folder).join()) !== 0
      ) {
        this.items = newValue;
      }
    },
    folders: {
      deep: true,
      handler(newValue) {

        // find current folder

        let current = this.items.findIndex(item => item.folder === this.currentFolder);

        if (this.items[current + 1]) {
          let ndx = newValue.findIndex(item => item.id === this.items[current + 1].folder);

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