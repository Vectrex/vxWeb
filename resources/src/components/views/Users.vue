<script setup>
  import Sortable from "@/components/vx-vue/sortable.vue";
  import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';
  import Confirm from "@/components/vx-vue/confirm.vue";
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
    del (id) {
      console.log(id);
    }
  }
}
</script>
