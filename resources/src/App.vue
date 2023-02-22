<script setup>
  import { computed } from "vue";
  import MessageToast from "@/components/message-toast.vue";
  import Sidebar from "@/components/app/Sidebar.vue";
  import Headerbar from "@/components/app/Headerbar.vue";
  import Logo from "@/components/misc/logo.vue";
</script>
<template>
  <div class="fixed inset-y-0 flex w-64 flex-col" v-if="$route.name !== 'login'">
    <div class="flex flex-grow flex-col overflow-y-auto bg-vxvue">
      <div class="flex flex-col justify-end p-4 items-start h-20 text-white">
        <logo class="h-8 block" />
      </div>
      <div class="flex flex-1 flex-col">
        <sidebar class="flex-1 space-y-1 px-2 pb-4" />
      </div>
    </div>
  </div>

  <main :class="['flex flex-1 flex-col', { 'pl-64': $route.name !== 'login'}]">
    <div class="sticky top-0 z-10 flex h-20 flex-shrink-0 bg-vxvue-800 shadow px-8" v-if="$route.name !== 'login'">
      <headerbar />
    </div>
    <div :class="[{'px-8 pt-6': $route.name !== 'login' }]">
      <div>
        <router-view
            @notify="notify"
            @authenticate="authenticate"
        />
      </div>
    </div>
  </main>

  <message-toast
      :active="toast.active"
      :class="toast.css"
      :message="toast.message"
      @close="toast.active = false"
      @timeout="toast.active = false"
  />
</template>

<script>
export default {
  name: 'App',
  expose: ['notify'],
  data() {
    return {
      auth: {},
      toast: {}
    }
  },
  provide () {
    return {
      auth: computed(() => this.auth)
    }
  },
  created () {
  },
  methods: {
    authenticate (event) {
      this.auth = event;
    },
    notify (data) {
      this.toast = {
        active: true,
        message: data.message || (data.success ? 'Success!' : 'Failure!'),
        css: data.success ? 'bg-green-700 text-white' : 'bg-red-700 text-white'
      }
    }
  }

}
</script>
