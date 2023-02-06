<script setup>
  import PasswordInput from "@/components/formelements/password-input.vue";
  import SimpleFetch from "@/util/simple-fetch";
</script>

<template>
  <h1>Meine Einstellungen</h1>

  <div class="form-sect">
      <div class="form-sect">
        <div v-for="element in elements" class="form-group">
          <label class="form-label col-3" :for="element.model + '_' + element.type" :class=" { required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
          <div class="col-9">
            <component
                :is="element.type || 'input'"
                :id="element.model + '_' + element.type"
                :name="element.model"
                v-model="form[element.model]"
            >
            </component>
            <p v-if="errors[element.model]" class="form-input-hint vx-error-box error">{{ errors[element.model] }}</p>
          </div>
        </div>
      </div>
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
        <button name="submit_profile" type='button' class='btn btn-success' :class="{'loading': loading}" :disabled="loading" @click="submit">Änderungen speichern</button>
      </div>
    </div>
</template>

<script>

export default {
  name: 'ProfileView',
  inject: ['api'],
  emits: ['notify'],
  data() {
    return {
      form: {},
      loading: false,
      elements: [
        { model: 'username', label: 'Username', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { model: 'email', label: 'E-Mail', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { model: 'name', label: 'Name', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
        { type: 'password-input', model: 'new_PWD', label: 'Neues Passwort', attrs: { maxlength: 128, autocomplete: "off" } },
        { type: 'password-input', model: 'new_PWD_verify', label: 'Passwort wiederholen', attrs: { maxlength: 128, autocomplete: "off" } }
      ]
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
      let response = await SimpleFetch(this.api + 'profile_data', 'POST', {}, JSON.stringify(this.form));
      this.loading = false;
    }
  }
}
</script>
