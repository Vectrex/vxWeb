<script setup>
  import PasswordInput from "@/components/formelements/password-input.vue";
  import Spinner from "@/components/misc/spinner.vue";
  import SimpleFetch from "@/util/simple-fetch";
</script>

<template>
    <div class="space-y-4">
      <div v-for="field in fields">
        <label :for="field.model + '-' + (field.type || 'input')" :class=" { required: field.required, 'text-red-600': errors[field.model] }">{{ field.label }}</label>
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
          <p v-if="errors[field.model]" class="text-sm text-red-600">{{ errors[field.model] }}</p>
        </div>
      </div>
    </div>

    <template v-if="notifications.length">

      <div>Benachrichtigungen</div>

      <div class="space-y-4">
        <div class="form-group" v-for="notification in notifications">
          <label class="form-switch"><input name="notification[]" v-bind:value="notification.alias" type="checkbox" v-model="form.notifications"><i class="form-icon"></i>{{ notification.label }}</label>
        </div>
      </div>
    </template>

    <div class="mt-4 pt-4 border-t border-slate-700">
      <div class="form-group off-3">
        <spinner class="h-5 w-5" v-if="busy" />
        <button name="submit_profile" type='button' class="button success" :disabled="busy" @click="submit">Änderungen speichern</button>
      </div>
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
    },
    message () {
      return this.response ? this.response.message : "";
    }
  },

  async created () {
    let response = await SimpleFetch(this.api + 'profile_data')
    this.notifications = response.notifications;
    if (response.formData) {
      this.form = response.formData;
    }
  },

  methods: {
    async submit() {
      this.busy = true;
      this.response = await SimpleFetch(this.api + 'profile_data', 'POST', {}, JSON.stringify(this.form));
      this.busy = false;
    }
  }
}
</script>
