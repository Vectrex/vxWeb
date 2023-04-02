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
      key-property="id"
  >
    <template v-slot:action="slotProps">
      <div class="flex space-x-2 justify-end">
        <a class="icon-link" href="#" @click.prevent="$router.push({ name: 'pageEdit', params: { id: slotProps.row.id }})"><PencilSquareIcon class="w-5 h-5"/></a>
        <a class="icon-link" href="#" @click.prevent="del(slotProps.row.id)"><TrashIcon class="w-5 h-5" /></a>
      </div>
    </template>
  </sortable>

  <teleport to="body">
    <alert
        ref="confirm"
        header-class="bg-error text-white"
        :buttons="[
            { label: 'Löschen!', value: true, 'class': 'button alert' },
            { label: 'Abbrechen', value: false, 'class': 'button cancel' }
          ]"
    />
  </teleport>
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
  },
  methods: {
    async del (id) {
      if(await this.$refs.confirm.open('Seite löschen', "Soll die Seite mit allen Revisionen wirklich gelöscht werden?")) {
        let response = await this.$fetch(this.api + 'page/' + id, 'DELETE');
        if (response.success) {
          this.pages.splice(this.pages.findIndex(item => id === item.id), 1);
          this.$emit('notify', {message: 'Seite wurde erfolgreich gelöscht.', success: true});
        } else {
          this.$emit('notify', {message: response.message || 'Es ist ein Fehler aufgetreten!', success: false});
        }
      }
    }
  }
}
</script>
