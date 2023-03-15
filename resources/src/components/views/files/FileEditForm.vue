<script setup>
  import SubmitButton from "@/components/app/SubmitButton.vue";
  import FormTitle from "@/components/views/shared/FormTitle.vue";
  import { formatFilesize } from '@/util/format-filesize';
</script>

<template>
  <div>
    <form-title @cancel="$emit('cancel')" class="w-sidebar">{{ fileInfo.name }}</form-title>
    <div class="overflow-hidden h-[calc(100vh-6rem)]">
      <div class="h-full overflow-y-auto">
        <div class="pt-16 pb-4">
          <img :src="fileInfo.thumb" v-if="(fileInfo.mimetype || '').startsWith('image')" class="w-full">
          <div class="px-4 py-2 bg-slate-100 space-y-2">
            <span class="w-1/3 inline-block">Typ</span><span class="w-2/3 inline-block">{{ fileInfo.mimetype }}</span>
            <span class="w-1/3 inline-block">Link</span><span class="w-2/3 inline-block"><a :href="fileInfo.url" target="_blank">{{ fileInfo.name }}</a></span>
            <template v-if="fileInfo.cache">
              <span class="w-1/3 inline-block">Cache</span><span class="w-2/3 inline-block">{{ fileInfo.cache.count }} Dateien, {{ formatFilesize(fileInfo.cache.totalSize) }}</span>
            </template>
          </div>
        </div>
        <div class="space-y-8 overflow-y-auto pb-4">
          <div class="space-y-4">
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
            <div class="px-4">
              <submit-button :busy="busy" @submit="submit">Daten übernehmen</submit-button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FileEditForm',
  inject: ['api'],
  emits: ['cancel', 'response-received'],
  props: {
    id: Number
  },
  data () {
    return {
      form: {},
      fileInfo: {},
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
        this.fileInfo = response.fileInfo || {};
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
        this.$emit('response-received', { success: true, message: response.message, payload: response.form || null});
      }
      else {
        this.errors = response.errors || {};
        this.$emit('response-received', { success: false, message: response.message });
      }
    }
  },
  formatFilesize: formatFilesize
}
</script>