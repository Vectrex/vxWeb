<script setup>
  import PasswordInput from "@/components/vx-vue/password-input.vue";
  import Headline from "@/components/app/Headline.vue";
  import SubmitButton from "@/components/app/SubmitButton.vue";
</script>

<template>
  <teleport to="#tools">
    <headline>{{ $route.meta?.heading }}</headline>
  </teleport>

  <div class="space-y-4 pb-4">
    <div class="space-y-4">
        <div v-for="field in fields">
          <label :for="field.model + '-' + (field.type || 'input')" :class=" { required: field.required, 'text-error': errors[field.model] }">{{ field.label }}</label>
          <div>
            <input
                class="form-input w-96"
                v-if="!field.type"
                :id="field.model + '-input'"
                v-model="form[field.model]"
            />
            <component :is="field.type"
                v-else
                :id="field.model + '-' + field.type"
                v-model="form[field.model]"
                class="w-96"
            />
            <p v-if="errors[field.model]" class="text-sm text-error">{{ errors[field.model] }}</p>
          </div>
        </div>
      </div>

      <template v-if="notifications.length">

        <div class="relative">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-slate-300" />
          </div>
          <div class="relative flex justify-start">
            <span class="bg-white pr-3 text-base font-semibold italic leading-6 text-slate-900">Benachrichtigungen</span>
          </div>
        </div>

        <div class="space-y-4">
          <div class="space-x-2" v-for="notification in notifications">
            <label class="space-x-2">
              <input name="notification[]" v-bind:value="notification.alias" type="checkbox" class="form-checkbox" v-model="form.notifications" />
              <span>{{ notification.label }}</span>
            </label>
          </div>
        </div>
      </template>

    <submit-button :busy="busy" @submit="submit">Änderungen speichern</submit-button>
  </div>
</template>

<script>

export default {
  components: {'password-input': PasswordInput },
  name: 'ProfileView',
  emits: ['notify'],
  inject: ['api'],
  data() {
    return {
      form: {},
      busy: false,
      response: {},
      fields: [
        { model: 'username', label: 'Username', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { model: 'email', label: 'E-Mail', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { model: 'name', label: 'Name', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { type: 'password-input', model: 'new_PWD', label: 'Neues Passwort', attrs: { maxlength: 128, autocomplete: "off" } },
        { type: 'password-input', model: 'new_PWD_verify', label: 'Passwort wiederholen', attrs: { maxlength: 128, autocomplete: "off" } }
      ],
      notifications: []
    }
  },

  computed: {
    errors () {
      return this.response ? (this.response.errors || {}) : {};
    }
  },

  async created () {
    let response = await this.$fetch(this.api + 'profile_data')
    this.notifications = response.notifications;
    if (response.formData) {
      this.form = response.formData;
    }
  },
  methods: {
    async submit() {
      this.busy = true;
      this.response = await this.$fetch(this.api + 'profile_data', 'POST', {}, JSON.stringify(this.form));
      this.busy = false;
      this.$emit('notify', this.response);
    }
  }
}
</script>
