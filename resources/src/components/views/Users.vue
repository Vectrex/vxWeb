<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import Alert from "@/components/vx-vue/alert.vue";
  import Headline from "@/components/app/Headline.vue";
  import UserForm from "@/components/views/users/UserForm.vue";
  import { PencilSquareIcon, TrashIcon, PlusIcon } from '@heroicons/vue/24/solid';
</script>

<template>
  <teleport to="#tools">
    <headline><span>Benutzer</span>
      <button
        class="icon-link !text-vxvue-700 border-transparent !hover:border-vxvue-700"
        type="button"
        @click="add"
      >
        <plus-icon class="w-5 h-5" />
      </button>
    </headline>
  </teleport>
  <div class="rounded overflow-hidden">
    <sortable
        :rows="users"
        :columns="cols"
        class="w-full"
    >
      <template v-slot:action="slotProps">
        <div class="flex space-x-2 justify-end" v-if="currentUser.username !== slotProps.row.username">
          <a class="icon-link" href="#" @click.prevent="edit(slotProps.row.id)"><PencilSquareIcon class="w-5 h-5"/></a>
          <a class="icon-link" href="#" @click.prevent="del(slotProps.row.id)"><TrashIcon class="w-5 h-5" /></a>
        </div>
      </template>
    </sortable>
  </div>

  <teleport to="body">
    <transition name="fade">
      <div
          class="z-10 fixed right-0 bottom-0 top-24 left-0 bg-black/20 backdrop-blur-sm"
          v-if="formShown"
          @click.stop="formShown = null"
      />
    </transition>
    <transition name="slide-from-right">
      <user-form
          v-if="formShown"
          @cancel="formShown = null"
          @response-received="handleResponse"
          :id="editData.id"
          :title="editData.id ? 'Benutzer bearbeiten' : 'Benutzer anlegen'"
          class="z-20 fixed top-24 bottom-0 shadow-gray shadow-lg bg-white w-sidebar right-0"
      />
    </transition>
  </teleport>

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
      formShown: false,
      editData: {
        id: null
      }
    }
  },
  async created () {
    this.currentUser = JSON.parse(sessionStorage.getItem('currentUser'));
    this.users = (await this.$fetch(this.api + 'users/init')).users || [];
  },
  methods: {
    edit (id) {
      this.editData.id = id;
      this.formShown = true;
    },
    add () {
      this.editData.id = null;
      this.formShown = true;
    },
    handleResponse (event) {
      if (event.payload?.id) {
        let ndx = this.users.findIndex(item => item.id === event.payload.id);
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
      if (await this.$refs.confirm.open("Benutzer löschen", "Soll der Benutzer wirklich entfernt werden?")) {
        let response = await this.$fetch(this.api + 'users/' + id, 'DELETE');
        if (response.id) {
          let ndx = this.users.findIndex(row => row.id === response.id);
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
