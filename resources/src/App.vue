<script setup>
  import { computed } from "vue";
  import MessageToast from "@/components/message-toast.vue";
  import Sidebar from "@/components/app/Sidebar.vue";
  import Headerbar from "@/components/app/Headerbar.vue";
  import Logo from "@/components/misc/logo.vue";
</script>
<template>
  <div class="fixed inset-y-0 flex w-64 flex-col" v-if="$route.name !== 'login'">
    <div class="flex flex-grow flex-col overflow-y-auto bg-vxvue pt-5">
      <div class="flex flex-shrink-0 items-center px-4">
        <logo class="h-8 text-white" />
      </div>
      <div class="mt-5 flex flex-1 flex-col">
        <sidebar class="flex-1 space-y-1 px-2 pb-4" />
      </div>
    </div>
  </div>

  <main>
    <div :class="['flex flex-1 flex-col', { 'pl-64': $route.name !== 'login'}]">
      <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white shadow" v-if="$route.name !== 'login'">
        <headerbar />
      </div>
      <h1 class="px-8 pt-6 text-2xl font-semibold text-slate-900" v-if="$route.meta?.label || $route.meta?.heading">{{ $route.meta.label || $route.meta.heading }}</h1>
      <div :class="{ 'max-w-7xl px-8' :  $route.name !== 'login' } ">
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
        css: data.success ? 'bg-green-800 text-white' : 'bg-red-800 text-white'
      }
    }
  }

}
</script>
