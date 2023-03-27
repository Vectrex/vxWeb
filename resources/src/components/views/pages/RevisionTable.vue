<script setup>
  import DateFunctions from "@/util/date-functions";
  import FormSwitch from "@/components/vx-vue/form-switch.vue";
  import { EyeIcon, TrashIcon} from '@heroicons/vue/24/solid';
</script>

<template>
  <table>
    <thead>
      <tr>
        <th>Angelegt um</th>
        <th></th>
        <th>aktiv</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="revision in sortedRevisions" :key="revision.id">
        <td>{{ formatDateTime(revision.firstCreated, 'y-mm-dd h:i:s') }}</td>
        <td>
          <button class="icon-link tooltip" type="button" data-tooltip="Ansicht" @click="$emit('load-revision', revision)"><eye-icon class="h-5 w-5" /></button>
        </td>
        <td>
          <form-switch @update:model-value="$emit('activate-revision', revision)" :disabled="revision.active" />
        </td>
        <td>
          <button class="icon-link tooltip" type="button" data-tooltip="Löschen" @click="$emit('delete-revision', revision)" v-if="!revision.active"><trash-icon class="h-5 w-5" /></button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
export default {
  name: "RevisionTable",
  emits: ['activate-revision', 'load-revision'],
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
