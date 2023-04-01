<script setup>
  import DateFunctions from "@/util/date-functions";
  import FormSwitch from "@/components/vx-vue/form-switch.vue";
  import { EyeIcon, TrashIcon} from '@heroicons/vue/24/solid';
</script>

<template>
  <table class="w-1/3">
    <thead>
      <tr>
        <th>Angelegt um</th>
        <th></th>
        <th>aktiv</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(revision, ndx) in sortedRevisions" :key="revision.id" :class="ndx % 2 ? 'bg-slate-200' : ''">
        <td class="px-4 py-2">{{ formatDateTime(revision.firstCreated, 'y-mm-dd h:i:s') }}</td>
        <td class="px-4 py-2">
          <button class="icon-link tooltip" type="button" data-tooltip="Ansicht" @click="$emit('load-revision', revision)"><eye-icon class="h-5 w-5" /></button>
        </td>
        <td class="px-4 py-2">
          <form-switch @update:model-value="$emit('activate-revision', revision)" :model-value="revision.active" :disabled="revision.active" />
        </td>
        <td class="px-4 py-2">
          <button class="icon-link tooltip" type="button" data-tooltip="Löschen" @click="$emit('delete-revision', revision)" v-if="!revision.active"><trash-icon class="h-5 w-5" /></button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: "RevisionTable",
  emits: ['activate-revision', 'load-revision', 'delete-revision'],
  props: {
    revisions: {
      type: Array, default: []
    }
  },
  computed: {
    sortedRevisions() {
      return this.revisions.slice().sort((a, b) => a.firstCreated < b.firstCreated ? 1 : -1);
    }
  },
  methods: {
    formatDateTime: DateFunctions.formatDate
  }

}
</script>
