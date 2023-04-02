<script setup>
  import MessageToast from "@/components/message-toast.vue";
  import MainMenu from "@/components/app/MainMenu.vue";
  import AccountInfo from "@/components/app/AccountInfo.vue";
  import Headerbar from "@/components/app/Headerbar.vue";
  import Logo from "@/components/misc/logo.vue";
  import { Bars3Icon } from '@heroicons/vue/24/solid';
</script>
<template>
  <div class="flex w-full">

    <div :class="['min-h-screen flex flex-col transition-all duration-100', sideBarExpanded ? 'w-80' : 'w-16']" v-if="$route.name !== 'login'">
      <div class="flex flex-grow flex-col overflow-y-auto bg-vxvue">
        <div class="pb-2 pl-4 h-24 bg-vxvue-600 flex items-end pr-2 space-x-2">
          <a href="#" @click.prevent="sideBarExpanded = !sideBarExpanded"><bars3-icon class="h-8 w-8 text-white flex-shrink-0" /></a>
          <logo class="text-vxvue-500 w-44" v-if="sideBarExpanded" />
        </div>
        <div class="flex flex-1 flex-col">
          <main-menu class="flex-1 space-y-1 px-2 py-6" :expanded="sideBarExpanded" />
        </div>
        <div class="p-4 h-32 border-t border-t-white">
          <account-info @authenticate="authenticate" :user="user" :expanded="sideBarExpanded" />
        </div>
      </div>
    </div>

    <div class="flex-1 flex flex-col min-h-screen">
      <div class="h-24 flex flex-1 items-end pb-2 bg-white px-8 shadow" v-if="$route.name !== 'login'">
        <headerbar />
      </div>
      <div :class="['overflow-hidden', $route.name === 'login' ? 'h-screen' : 'h-[calc(100vh-var(--header-height))]']">
        <main class="w-full h-full overflow-y-auto flex flex-1 flex-col">
          <div :class="[{'px-8 pt-6': $route.name !== 'login' }]">
            <div>
              <router-view
                  @notify="notify"
                  @authenticate="authenticate"
              />
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>

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
      sideBarExpanded: true,
      user: {},
      toast: {},
      intId: null
    }
  },
  created () {
    let currentUser = sessionStorage.getItem("currentUser");
    if (currentUser) {
      this.user = JSON.parse(currentUser);
    }
    else {
      this.user = {};
      this.$router.push({ name: 'login' });
    }
  },
  methods: {
    authenticate (event) {
      if (!event) {
        sessionStorage.removeItem("currentUser");
        sessionStorage.removeItem("bearerToken");
        this.user = {};
        this.$router.push({ name: 'login' });
      }
      else {
        sessionStorage.setItem("currentUser", JSON.stringify(event.user));
        this.$router.push({ name: 'profile' });
        this.user = event.user;
      }
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
