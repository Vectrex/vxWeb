<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
  import Alert from "@/components/vx-vue/alert.vue";
  import SimpleFetch from "@/util/simple-fetch";
</script>

<template>
  <div class="rounded overflow-hidden">
    <sortable
        :rows="users"
        :columns="cols"
        class="w-full"
    >
      <template v-slot:action="slotProps">
        <div class="flex space-x-2 justify-end" v-if="currentUser.username !== slotProps.row.username">
          <a class="icon-link" href="#" @click.prevent="edit(slotProps.row.adminid)"><PencilSquareIcon class="w-5 h-5"/></a>
          <a class="icon-link" href="#" @click.prevent="del(slotProps.row.adminid)"><TrashIcon class="w-5 h-5" /></a>
        </div>
      </template>
    </sortable>
  </div>

  <teleport to="body">
    <alert
        ref="delConfirm"
        :buttons="[
          { label: 'Löschen!', value: true, 'class': 'button alert' },
          { label: 'Abbrechen', value: false, 'class': 'button' }
        ]"
    />
  </teleport>
</template>

<script>
export default {
  name: "Users",
  emits: ['notify'],
  inject: ['api'],
  data () {
    return {
      users: [],
      currentUser: {},
      cols: [
        { label: "Username", sortable: true, width: "w-1/4", prop: "username" },
        { label: "Name", sortable: true, width: "w-1/6", prop: "name" },
        { label: "Email", prop: "email" },
        { label: "Gruppe", sortable: true, width: "w-1/6", prop: "alias" },
        { label: "", width: "w-1/12", prop: "action", cssClass: "text-right" }
      ]
    }
  },
  async created () {
    this.currentUser = JSON.parse(sessionStorage.getItem('currentUser'));
    this.users = (await SimpleFetch (this.api + 'users_init')).users || [];
  },
  methods: {
    edit (id) {
      console.log(id);
    },
    async del (id) {
      if (await this.$refs.delConfirm.open("Benutzer löschen", "Soll der Benutzer wirklich entfernt werden?")) {
        let response = await SimpleFetch(this.api + 'users/' + id, 'DELETE');
        if (response.id) {
          let ndx = this.users.findIndex(row => row.adminid === response.id);
          if (ndx !== -1) {
            this.users.splice(ndx, 1);
            this.$emit('notify', { message: 'Benutzer wurde erfolgreich gelöscht.', success: true });
          }
        }
        else {
          this.$emit('notify', { message: response.message || 'Es ist ein Fehler aufgetreten!', success: false });
        }
      }
    }
  }
}
</script>
