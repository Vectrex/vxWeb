<script setup>
  import MessageToast from "@/components/message-toast.vue";
  import MainMenu from "@/components/app/MainMenu.vue";
  import AccountInfo from "@/components/app/AccountInfo.vue";
  import Headerbar from "@/components/app/Headerbar.vue";
  import Logo from "@/components/misc/logo.vue";
</script>
<template>
  <div class="flex w-full">

    <div class="min-h-screen flex w-64 flex-col" v-if="$route.name !== 'login'">
      <div class="flex flex-grow flex-col overflow-y-auto bg-vxvue">
        <div class="pb-2 pl-4 pr-12 h-24 bg-vxvue-600 flex flex-col justify-end">
          <logo class="text-vxvue-500"/>
        </div>
        <div class="flex flex-1 flex-col">
          <main-menu class="flex-1 space-y-1 px-2 py-8" />
        </div>
        <div class="p-4 h-24 border-t border-t-white">
          <account-info @authenticate="authenticate" :user="user" />
        </div>
      </div>
    </div>

    <div class="flex-1 flex flex-col min-h-screen">
      <div class="h-24 flex flex-1 items-end pb-2 bg-white px-8 shadow" v-if="$route.name !== 'login'">
        <headerbar />
      </div>
      <div :class="['overflow-hidden', $route.name === 'login' ? 'h-screen' : 'h-[calc(100vh-6rem)]']">
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
