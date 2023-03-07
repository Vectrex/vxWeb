<script setup>
  import PasswordInput from "@/components/vx-vue/password-input.vue";
  import Spinner from "@/components/misc/spinner.vue";
  import Headline from "@/components/app/Headline.vue";
</script>

<template>
  <teleport to="#tools">
    <headline>{{ $route.meta?.heading }}</headline>
  </teleport>

  <div class="space-y-4">
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
          <div class="space-x-2" v-for="notification in notifications">
            <label class="space-x-2">
              <input name="notification[]" v-bind:value="notification.alias" type="checkbox" class="form-checkbox" v-model="form.notifications" />
              <span>{{ notification.label }}</span>
            </label>
          </div>
        </div>
      </template>

    <div class="flex space-x-2 items-center">
      <button class="button success" type="button" @click="submit" :disabled="busy">
        Änderungen speichern
      </button>
      <spinner v-if="busy" class="text-green-700" />
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
    }
  }
}
</script>
