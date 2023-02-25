<script setup>
  import Spinner from "@/components/misc/spinner.vue";
  import FormTitle from "@/components/views/shared/FormTitle.vue";
  import { formatFilesize } from '@/util/format-filesize';
</script>

<template>
  <div>
    <form-title @cancel="$emit('cancel')" class="w-sidebar">{{ form.path }}</form-title>
    <div class="space-y-8 overflow-y-auto pt-8">
      <div class="space-y-4 pt-16">
        <div v-for="field in fields" class="px-4">
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
              v-bind="field.attrs"
          />
          <textarea
              v-else-if="field.type === 'textarea'"
              class="w-full form-textarea"
              :id="field.model + '-' + field.type"
              v-model="form[field.model]"
          />
          <p v-if="errors[field.model]" class="text-sm text-error">{{ errors[field.model] }}</p>
        </div>
        <div class="flex justify-center space-x-2 items-center">
          <button class="button success" type="button" @click="submit" :disabled="busy">
            Daten übernehmen
          </button>
          <spinner v-if="busy" class="text-green-700" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileEditForm',
  inject: ['api'],
  emits: ['cancel', 'notify'],
  props: {
    id: Number
  },
  data () {
    return {
      form: {},
      fileinfo: {},
      errors: {},
      busy: false,
      fields: [
        { model: 'title', label: 'Titel' },
        { model: 'subtitle', label: 'Untertitel' },
        { type: 'textarea', model: 'description', label: 'Beschreibung' },
        { model: 'customsort', label: 'Sortierziffer', attrs: { type: "number" } },
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
        const response = await this.$fetch(this.api + 'file/' + newValue);
        this.form = response.form || {};
        this.fileinfo = response.fileinfo || {};
      },
      immediate: true
    }
  },
  methods: {
    async submit() {
      this.busy = true;
      let response = await this.$fetch(this.api + 'file/' + this.id, 'PUT', {}, JSON.stringify(this.sanitizedForm));
      this.busy = false;

      if (response.success) {
        this.errors = {};
        this.$emit('notify', { success: true, message: response.message, payload: response.form || null});
      }
      else {
        this.errors = response.errors || {};
        this.$emit('notify', { success: false, message: response.message });
      }
    }
  },
  formatFilesize: formatFilesize
}
</script>