<script setup>
  import PasswordInput from "@/components/formelements/password-input.vue";
  import Spinner from "@/components/misc/spinner.vue";
  import Logo from "@/components/misc/logo.vue";
  import { HomeIcon } from '@heroicons/vue/20/solid';
  import SimpleFetch from "@/util/simple-fetch";
</script>

<template>
  <div class="min-h-screen bg-slate-200 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <transition name="appear" appear>
      <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl shadow" @keydown.enter="submit">

        <div class="bg-vxvue px-10 pb-0 pt-16 text-white flex space-x-2 items-baseline rounded-t">
          <logo class="w-1/2" />
          <span class="text-2xl">Administration</span>
        </div>

        <div class="bg-white py-8 px-4 sm:px-10 space-y-4">
          <input v-model="form.username" type="text" class="form-input w-full" placeholder="Username" />
          <password-input v-model="form.password" class="w-full" placeholder="Passwort" />

          <div class="flex justify-between items-center">
            <div class="flex space-x-2 items-center">
              <button
                  type="button"
                  :disabled="busy"
                  class="button success"
                  @click="submit"
              >
                Anmelden
              </button>
              <spinner v-if="busy" class="text-green-500" />
            </div>
            <span class="flex space-x-1"><home-icon class="w-5 h-5"/><a :href="siteLink" class="link text-rose-600 hover:text-rose-500">{{ siteLabel }}</a></span>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "LoginView",
  emits: ['authenticate', 'notify'],
  inject: ['api'],
  data () {
    return {
      form: {
        username: '',
        password: ''
      },
      busy: false,
      siteLink: window.location.protocol + '//:' + window.location.host,
      siteLabel: window.location.host
    }
  },
  created () {
    sessionStorage.removeItem("bearerToken");
  },
  methods: {
    async submit () {
      if (this.form.username.trim() && this.form.password.trim()) {
        this.busy = true;
        let response = await SimpleFetch(this.api + "login", 'POST', {}, JSON.stringify(this.form));
        this.busy = false;

        if (response.bearerToken) {
          this.$emit('authenticate', response);
          this.$router.push( { name: 'profile' } );
          sessionStorage.setItem("bearerToken", response.bearerToken);
          sessionStorage.setItem("currentUser", JSON.stringify(response.user));
        }
        else {
          this.$emit('notify', response);
        }
      }
    }
  }
}
</script>
