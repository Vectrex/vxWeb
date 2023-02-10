<script setup>
  import SimpleFetch from "@/util/simple-fetch";
  import Spinner from "@/components/misc/spinner.vue";
  import FormTitle from "@/components/views/shared/FormTitle.vue";
  import FormSelect from "@/components/formelements/form-select.vue";
  import PasswordInput from "@/components/formelements/password-input.vue";
</script>
<template>
  <div class="space-y-4">
    <form-title @cancel="$emit('cancel')">Benutzer anlegen/bearbeiten</form-title>
    <div class="space-y-2 pt-16">
      <div v-for="field in fields" class="px-4">
        <label
            :class="{ 'text-red-500': [errors[field.model]], 'required': field.required }"
            :for="field.model + '-' + field.type || 'input'"
        >
          {{ field.label }}
        </label>
        <input
            v-if="!field.type"
            :id="field.model + '-input'"
            class="w-full form-input"
            v-model="form[field.model]"
        />
        <component :is="field.type"
            v-else
           class="w-full"
            :id="field.model + '-' + field.type"
            v-model="form[field.model]"
            :options="field.options"
        />
        <p v-if="errors[field.model]" class="text-sm text-red-600">{{ errors[field.model] }}</p>
      </div>
    </div>
    <div class="flex justify-center space-x-2">
      <button class="button success" type="button" @click="submit" :disabled="busy">
        {{ form.id ? 'Daten übernehmen' : 'User anlegen' }}
      </button>
      <spinner v-if="busy" />
    </div>
  </div>
</template>

<script>
  export default {
    name: "CustomerForm",
    inject: ['api'],
    emits: ['cancel', 'notify'],
    components: {
      'form-select': FormSelect,
      'password-input': PasswordInput
    },
    props: {
      id: { type: String, default: null },
      data: { type: Object, default: {} },
      options:  { type: Object, default: {} }
    },

    data: () => ({
        form: {},
        errors: {},
        busy: false,
        fields: [
            { model: 'username', label: 'Username', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
            { model: 'email', label: 'E-Mail', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
            { model: 'name', label: 'Name', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
            { type: 'form-select', model: 'admingroupsid', label: 'Gruppe', required: true, options: [] },
            { type: 'password-input', model: 'new_PWD', label: 'Neues Passwort', attrs: { maxlength: 128, autocomplete: "off" } },
            { type: 'password-input', model: 'new_PWD_verify', label: 'Passwort wiederholen', attrs: { maxlength: 128, autocomplete: "off" } }
        ]
    }),

    computed: {
      missingRequired () {
        return this.fields.some(item => item.required && (!this.form[item.prop] || !this.form[item.prop].trim()));
      },
      sanitizedForm () {
        let sanitized = {};

        for (const [key, value] of Object.entries(this.form)) {
          if(value !== null) {
            sanitized[key] = value;
          }
        }

        return sanitized;
      }
    },
    watch: {
      data: {
        handler(newValue) {
          this.form = newValue;
        },
        immediate: true
      }
    },
    methods: {
      async submit() {
        this.busy = true;
        let response = await SimpleFetch(this.api + 'user', this.id ? 'PUT' : 'POST', {}, JSON.stringify(this.form));
        this.busy = false;

        if (response.success) {
          this.$emit('notify', { success: true, message: response.message });
        }
        else {
          this.errors = response.errors || {};
          this.$emit('notify', { success: false, message: response.message });
        }
      }
    }
  }
</script>