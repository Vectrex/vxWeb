<script setup>
  import { SlickList, SlickItem, DragHandle } from 'vue-slicksort';
  import { EyeIcon, EyeSlashIcon, LinkIcon, Bars4Icon } from '@heroicons/vue/24/solid';
</script>
<template>
  <slick-list v-model:list="linkedFiles" lock-axis="y" @update:list="saveSort" useDragHandle>
    <slick-item v-for="(item, ndx) in linkedFiles" :key="item.id" :index="ndx" class="w-full space-x-4 py-2 flex items-center border-b last:border-none">
      <drag-handle class="cursor-pointer"><bars4-icon class="h-5 w-5" /></drag-handle>
      <div class="w-1/4">{{ item.filename }}</div>
      <div class="w-24 flex justify-center items-center">
        <img :src="item.src" alt="" v-if="item.isThumb && item.src" class="thumb">
        <div class="overflow-ellipsis whitespace-nowrap overflow-hidden" v-else>{{ item.type }}</div>
      </div>
      <div class="w-24 flex justify-center items-center space-x-2">
        <button class="icon-link" data-tooltip="Verlinkung entfernen" type="button" @click="unlinkSort(item)">
          <link-icon class="h-5 w-5" />
        </button>
        <button class="icon-link" :data-tooltip="item.hidden ? 'Anzeigen' : 'Verstecken'" type="button" @click="toggleVisibility(item)">
          <component :is="item.hidden ? EyeIcon : EyeSlashIcon" class="h-5 w-5" />
        </button>
      </div>
      <a class="w-1/2" :href="'#'" @click.prevent="gotoFolder(item.folderid)">{{ item.path }}</a>
    </slick-item>
  </slick-list>
</template>

<script>
export default {
  name: "LinkedFiles",
  components: { EyeIcon, EyeSlashIcon },
  inject: ['api'],
  props: { articleId: { type: [Number, String], required: true }},
  data () {
    return {
      linkedFiles: []
    }
  },
  async created () {
    this.linkedFiles = await this.$fetch(this.api + 'article/' + this.articleId + '/linked-files');
  },
  methods: {
    async saveSort() {
      let ids = [];
      this.linkedFiles.forEach(f => ids.push(f.id));
      this.$fetch(this.api + 'article/' + this.articleId + '/linked-files', 'PUT', {}, JSON.stringify({ fileIds: ids }));
    },
    async unlinkSort (file) {
      let response = await this.$fetch(this.api + 'article/' + this.articleId + '/link-file', 'PUT', {}, JSON.stringify({ fileId: file.id }));
      if(response.success) {
        this.linkedFiles.splice(this.linkedFiles.findIndex(item => item === file), 1);
        this.$emit('update-linked');
      }
    },
    async toggleVisibility (file) {
      let response = await this.$fetch(this.api + 'article/' + this.articleId + '/toggle-linked-file', 'PUT', {}, JSON.stringify({ fileId: file.id }));
      if (response.success) {
        file.hidden = !!response.hidden;
      }
    },
    gotoFolder() {
    }
  }
}
</script>
