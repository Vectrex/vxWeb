<script setup>
  import PasswordInput from "@/components/formelements/password-input.vue";
  import Spinner from "@/components/misc/spinner.vue";
  import SimpleFetch from "@/util/simple-fetch";
</script>

<template>
  <h1>Meine Einstellungen</h1>

  <div class="form-sect">
      <div class="form-sect">
        <div v-for="element in elements" class="form-group">
          <label class="form-label col-3" :for="element.model + '_' + (element.type || 'form-input')" :class=" { required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
          <div class="col-9">
            <input
                v-if="!element.type"
                :id="element.model + '_input'"
                v-model="form[element.model]"
            />
            <password-input
                v-if="element.type === 'password-input'"
                :id="element.model + '_' + element.type"
                v-model="form[element.model]"
            />
            <p v-if="errors[element.model]" class="form-input-hint vx-error-box error">{{ errors[element.model] }}</p>
          </div>
        </div>
      </div>
    <input v-model="form['username']" />
    </div>

    <template v-if="notifications.length">
      <div class="divider text-center" data-content="Benachrichtigungen"></div>

      <div class="form-sect off-3">
        <div class="form-group" v-for="notification in notifications">
          <label class="form-switch"><input name="notification[]" v-bind:value="notification.alias" type="checkbox" v-model="form.notifications"><i class="form-icon"></i>{{ notification.label }}</label>
        </div>
      </div>
    </template>

    <div class="divider"></div>

    <div class="form-base">
      <div class="form-group off-3">
        <spinner class="h-5 w-5" v-if="loading" /><button name="submit_profile" type='button' class='button' :disabled="loading" @click="submit">Änderungen speichern</button>
      </div>
    </div>
</template>

<script>

export default {
  name: 'ProfileView',
  emits: ['notify'],
  inject: ['api'],
  data() {
    return {
      form: {},
      loading: false,
      response: {},
      elements: [
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
      this.loading = true;
      this.response = await SimpleFetch(this.api + 'profile_data', 'POST', {}, JSON.stringify(this.form));
      this.loading = false;
    }
  }
}
</script>
