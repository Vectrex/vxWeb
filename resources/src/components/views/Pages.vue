<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import Alert from "@/components/vx-vue/alert.vue";
  import Headline from "@/components/app/Headline.vue";
  import { PencilSquareIcon, TrashIcon, PlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
  <teleport to="#tools">
    <headline>
      <span>{{ $route.meta?.heading }}</span>
      <button
          class="icon-link !text-vxvue-700 border-transparent !hover:border-vxvue-700"
          type="button"
          @click="$router.push({ name: 'pageEdit' })"
      >
        <plus-icon class="w-5 h-5" />
      </button>
    </headline>
  </teleport>

  <sortable
      :rows="pages"
      :columns="cols"
      :sort-prop="initSort.column"
      :sort-direction="initSort.dir"
  />

</template>

<script>
export default {
  name: "Pages",
  inject: ['api'],
  data () {
    return {
      pages: [],
      cols: [
        { label: "Alias/Titel", sortable: true, prop: "alias" },
        { label: "Datei", sortable: true, prop: "template" },
        { label: "Inhalt", prop: "contents" },
        { label: "letzte Änderung", sortable: true, prop: "updated" },
        { label: "#Rev", sortable: true, prop: "revisionCount" },
        { label: "", prop: "action" }
      ],
      initSort: {}
    }
  },
  async created () {
    this.pages = await this.$fetch(this.api + 'pages');
  }
}
</script>
