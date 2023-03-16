<script setup>
  import Filemanager from "@/components/views/files/Filemanager.vue";
</script>
<template>
  <filemanager
    :columns="cols"
    :init-sort="initSort"
  >
    <template v-slot:linked="slotProps">
      <input v-if="!slotProps.row.isFolder" class="form-checkbox" type="checkbox" @click="handleLink(slotProps.row)" :checked="slotProps.row.linked" />
    </template>
  </filemanager>
</template>

<script>
export default {
  name: "ArticleFiles",
  inject: ['api'],
  props: ['articleId'],
  data () {
    return {
      cols: [
        {
          label: "Dateiname",
          sortable: true,
          prop: "name",
          sortAscFunction: (a, b) => {
            if (a.isFolder && !b.isFolder) {
              return -1;
            }
            return a.name.toLowerCase() === b.name.toLowerCase() ? 0 : a.name.toLowerCase() < b.name.toLowerCase() ? -1 : 1;
          },
          sortDescFunction: (a, b) => {
            if (a.isFolder && !b.isFolder) {
              return -1;
            }
            return a.name.toLowerCase() === b.name.toLowerCase() ? 0 : a.name.toLowerCase() < b.name.toLowerCase() ? 1 : -1;
          }
        },
        { label: "Link", sortable: true, prop: "linked" },
        { label: "Größe", sortable: true, prop: "size" },
        { label: "Typ/Vorschau", sortable: true, prop: "type" },
        { label: "Erstellt", sortable: true, prop: "modified" },
        { label: "", prop: "action" }
      ],
      initSort: {}
    }
  },
  methods: {
    async handleLink (row) {
      let response = this.$fetch(this.api + 'article/' + this.articleId + '/link-file', 'PUT', {}, JSON.encode({ fileId: row.id }));
      if(response.success) {
        row.linked = response.status === 'linked';
      }
    }
  }
}
</script>
