<template>
  <form action="/" class="form-horizontal" @submit.prevent>

    <div class="form-sect">
      <div class="form-sect">
        <div v-for="element in elements" class="form-group">
          <label class="form-label col-3" :for="element.model + '_' + element.type" :class=" { required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
          <div class="col-9">
            <component
                :is="element.type || 'form-input'"
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

  </form>
</template>

<script>

import SimpleFetch from "../../util/simple-fetch.js";
import PasswordInput from "../vx-vue/formelements/password-input";
import FormInput from "../vx-vue/formelements/form-input";

export default {
  components: {
    'password-input': PasswordInput,
    'form-input': FormInput
  },
  props: {
    url: { type: String, required: true },
    initialData: { type: Object, default: () => { return {} } },
    notifications: { type: Array }
  },

  data() {
    return {
      form: {},
      response: {},
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

  watch: {
    initialData (newValue) {
      this.form = newValue;
    }
  },

  methods: {
    async submit() {
      this.loading = true;
      this.$emit("request-sent");
      this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
      this.loading = false;
      this.$emit("response-received");
    }
  }
}
</script>