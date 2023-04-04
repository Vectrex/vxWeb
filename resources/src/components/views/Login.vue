<script setup>
  import PasswordInput from "@/components/vx-vue/password-input.vue";
  import SubmitButton from "@/components/misc/submit-button.vue";
  import Logo from "@/components/misc/logo.vue";
  import { HomeIcon } from '@heroicons/vue/20/solid';
</script>

<template>
  <div class="min-h-screen bg-slate-200 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <transition name="appear" appear>
      <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl shadow ring-1 ring-black ring-opacity-5" @keydown.enter="submit">

        <div class="bg-vxvue px-10 pb-0 pt-16 text-white flex space-x-2 items-baseline rounded-t">
          <logo class="w-1/2" />
          <span class="text-2xl">Administration</span>
        </div>

        <div class="bg-white py-8 px-4 sm:px-10 space-y-4">
          <input v-model="form.username" type="text" class="form-input w-full" placeholder="Username" />
          <password-input v-model="form.password" class="w-full" placeholder="Passwort" />

          <div class="flex justify-between items-center">
            <submit-button :busy="busy" @submit="submit">Anmelden</submit-button>
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
  methods: {
    async submit () {
      if (this.form.username.trim() && this.form.password.trim()) {
        this.busy = true;
        let response = await this.$fetch(this.api + "login", 'POST', {}, JSON.stringify(this.form));
        this.busy = false;
        if (response.bearerToken) {
          this.$emit('authenticate', response);
        }
        else {
          this.$emit('notify', response);
        }
      }
    }
  }
}
</script>
