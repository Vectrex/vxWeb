<script setup>
  import SimpleTree from "@/components/vx-vue/simple-tree/simple-tree.vue";
  import { urlQueryCreate } from '@/util/url-query';
</script>

<template>
  <div ref="container" class="modal" :class="{ active: show }">
    <div class="modal-container">
      <div class="modal-header">
        <a href="#close" class="btn btn-clear float-right" aria-label="Close" @click.prevent="cancel"></a>
        <div class="modal-title h5">{{ title }}</div>
      </div>
      <div class="modal-body">
        <simple-tree :branch="root" @branch-selected="folderSelected"></simple-tree>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'FolderTree',
    data () {
        return {
          show: false,
          resolve: null,
          reject: null,
          root: {}
        }
    },
    props: {
      title: { type: String, default: 'Zielordner wählen' }
    },
    methods: {
      async open (route, currentFolder) {

        this.root = await this.$fetch(urlQueryCreate(route, { folder: currentFolder }));
        this.show = true;

        return new Promise((resolve, reject) => {
          this.resolve = resolve;
          this.reject = reject;
        });
      },
      cancel () {
        this.show = false;
        this.resolve(false);
      },
      folderSelected (folder) {
        this.show = false;
        this.resolve(folder);
      }
    }
  }
</script>