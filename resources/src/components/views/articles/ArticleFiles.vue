<script setup>
  import Filemanager from "@/components/views/files/Filemanager.vue";
  import FormSwitch from "@/components/vx-vue/form-switch.vue";
  import { PencilSquareIcon, TrashIcon, DocumentMinusIcon, PlayIcon, DocumentPlusIcon } from '@heroicons/vue/24/solid';
</script>
<template>
  <filemanager
    :columns="cols"
    :init-sort="initSort"
    :request-parameters="{ articleId: articleId }"
    :folder="selectedFolder"
    @response-received="handleReceivedResponse"
    ref="fm"
  >
    <template v-slot:linked="slotProps">
      <form-switch v-if="!slotProps.row.isFolder" :model-value="slotProps.row.linked" @update:model-value="handleLink(slotProps.row)" />
    </template>

    <template v-slot:action="slotProps">
      <div class="flex items-center space-x-1">
        <template v-if="slotProps.row.isFolder">
          <button class="icon-link tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFolder(slotProps.row)">
            <pencil-square-icon class="h-5 w-5" />
          </button>
          <button class="icon-link tooltip" data-tooltip="Ordner leeren und löschen" @click="$refs.fm.delFolder(slotProps.row)">
            <trash-icon class="h-5 w-5" />
          </button>
        </template>
        <template v-else>
          <button class="icon-link tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFile(slotProps.row)">
            <pencil-square-icon class="h-5 w-5" />
          </button>
          <button class="icon-link flex items-center tooltip" data-tooltip="Verschieben" type="button" @click="$refs.fm.moveFile(slotProps.row)">
            <document-minus-icon class="h-5 w-5"/>
            <play-icon class="h-3 w-3" />
            <document-plus-icon class="h-5 w-5"/>
          </button>
          <button class="icon-link tooltip" data-tooltip="Löschen" type="button" @click="$refs.fm.delFile(slotProps.row)">
            <trash-icon class="h-5 w-5" />
          </button>
        </template>
      </div>
    </template>

  </filemanager>
</template>

<script>
export default {
  name: "ArticleFiles",
  inject: ['api'],
  props: ['articleId', 'selectedFolder'],
  emits: ['notify', 'update-linked'],
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
      let response = await this.$fetch(this.api + 'article/' + this.articleId + '/link-file', 'PUT', {}, JSON.stringify({ fileId: row.id }));
      if(response.success) {
        row.linked = response.status === 'linked';
        this.$emit('update-linked');
      }
    },
    handleReceivedResponse (event) {
      this.$emit('notify', event);
      if (['uploadFiles', 'delFile', 'delFolder', 'delSelection'].indexOf(event._method) !== -1) {
        this.$emit('update-linked');
      }
    }
  }
}
</script>
