<script setup>
  import SubmitButton from "@/components/misc/submit-button.vue";
  import FormDialog from "@/components/views/shared/FormDialog.vue";
</script>
<template>
  <form-dialog @cancel="$emit('cancel')">
      <template #title>{{ form.path }}</template>
      <template #content>
          <div class="space-y-4 pt-20 pb-4 px-4">
              <div v-for="field in fields">
                  <label
                          :class="{ 'text-error': errors[field.model], 'required': field.required }"
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
                  <textarea
                          v-else-if="field.type === 'textarea'"
                          class="w-full form-textarea"
                          :id="field.model + '-' + field.type"
                          v-model="form[field.model]"
                  />
                  <p v-if="errors[field.model]" class="text-sm text-error">{{ errors[field.model] }}</p>
              </div>
              <submit-button :busy="busy" @submit="submit">Daten übernehmen</submit-button>
          </div>
      </template>
  </form-dialog>
</template>

<script>
export default {
  name: 'FolderEditForm',
  inject: ['api'],
  emits: ['cancel', 'response-received'],
  props: {
    id: Number
  },
  data () {
    return {
      form: {},
      errors: {},
      busy: false,
      fields: [
        { model: 'title', label: 'Titel' },
        { type: 'textarea', model: 'description', label: 'Beschreibung' }
      ]
    }
  },
  computed: {
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
    id: {
      async handler(newValue) {
        const response = await this.$fetch(this.api + 'folder/' + newValue);
        this.form = response;
      },
      immediate: true
    }
  },
  methods: {
    async submit() {
      this.busy = true;
      let response = await this.$fetch(this.api + 'folder/' + this.id, 'PUT', {}, JSON.stringify(this.sanitizedForm));
      this.busy = false;

      if (response.success) {
        this.errors = {};
        this.$emit('response-received', { success: true, message: response.message, payload: response.form || null});
      }
      else {
        this.errors = response.errors || {};
        this.$emit('response-received', { success: false, message: response.message });
      }
    }
  }
}
</script>