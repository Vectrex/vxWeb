<script setup>
  import SimpleTree from "@/components/vx-vue/simple-tree/simple-tree.vue";
  import FormTitle from "@/components/views/shared/FormTitle.vue";
  import { urlQueryCreate } from '@/util/url-query';
</script>

<template>
    <form-title @cancel="cancel">Zielordner wählen</form-title>
    <div>
      <simple-tree :branch="root" @branch-selected="folderSelected"></simple-tree>
    </div>
</template>

<script>
  export default {
    name: 'FolderTree',
    expose: ['open'],
    data () {
        return {
          resolve: null,
          reject: null,
          root: {}
        }
    },
    methods: {
      async open (route, currentFolder) {

        this.root = await this.$fetch(urlQueryCreate(route, { folder: currentFolder }));

        return new Promise((resolve, reject) => {
          this.resolve = resolve;
          this.reject = reject;
        });
      },
      cancel () {
        this.resolve(false);
      },
      folderSelected (folder) {
        this.resolve(folder);
      }
    }
  }
</script>