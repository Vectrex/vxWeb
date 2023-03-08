<script setup>
  import Filemanager from "@/components/views/files/Filemanager.vue";
</script>
<template>
  <filemanager
    :columns="cols"
    :init-sort="initSort"
  />
</template>

<script>
export default {
  name: "ArticleFiles",
  data () {
    return {
      cols: [
        {
          label: "",
          prop: "checked"
        },
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
        { label: "Größe", sortable: true, prop: "size" },
        { label: "Typ/Vorschau", sortable: true, prop: "type" },
        { label: "Erstellt", sortable: true, prop: "modified" },
        { label: "", prop: "action" }
      ],
      initSort: {}
    }
  }
}
</script>
