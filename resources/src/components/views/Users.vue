<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import Alert from "@/components/vx-vue/alert.vue";
  import Headline from "@/components/app/Headline.vue";
  import UserForm from "@/components/views/users/UserForm.vue";
  import { PencilSquareIcon, TrashIcon, PlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
  <headline><span>Benutzer</span><a class="icon-link" href="#" @click.prevent="add"><plus-icon class="w-5 h-5" /></a></headline>
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
    <transition name="slide">
      <user-form
          v-if="showForm"
          @cancel="showForm = false"
          @notify="handleNotify"
          :id="editData.id"
          :title="editData.id ? 'Benutzer bearbeiten' : 'Benutzer anlegen'"
          class="fixed right-0 top-16 bottom-0 shadow-gray shadow-lg bg-white w-sidebar"
      />
    </transition>
  </teleport>

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
      ],
      showForm: false,
      editData: {
        id: null
      }
    }
  },
  async created () {
    this.currentUser = JSON.parse(sessionStorage.getItem('currentUser'));
    this.users = (await this.$fetch(this.api + 'users_init')).users || [];
  },
  methods: {
    edit (id) {
      this.editData.id = id;
      this.showForm = true;
    },
    add () {
      this.editData.id = null;
      this.showForm = true;
    },
    handleNotify (event) {
      if (event.payload?.adminid) {
        let ndx = this.users.findIndex(item => item.adminid === event.payload.adminid);
        if (ndx !== -1) {
          this.users[ndx] = event.payload;
        }
        else {
          this.users.push(event.payload);
        }
      }
      this.$emit('notify', event);
    },
    async del (id) {
      if (await this.$refs.delConfirm.open("Benutzer löschen", "Soll der Benutzer wirklich entfernt werden?")) {
        try {
          let response = await SimpleFetch(this.api + 'users/' + id, 'DELETE');
        }
        catch (e) {
          console.log(e);
        }
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
