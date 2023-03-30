<script setup>
  import SubmitButton from "@/components/app/SubmitButton.vue";
  import Tiptap from "@/components/misc/tiptap.vue";
</script>

<template>
  <div class="space-y-2">
    <div class="flex items-center flex-wrap">
      <label
          for="alias-input"
          :class="['required', { 'text-error': errors.alias }]"
      >Eindeutiger Name</label>
      <input id="alias-input"
           :value="form.alias"
           @input="form.alias = $event.target.value.toUpperCase()"
           class="form-input w-full"
           :disabled="form?.revisionId" maxlength="64"
      >
      <p v-if="errors.alias" class="text-sm text-error">{{ errors.alias }}</p>
    </div>

    <div class="flex items-center flex-wrap" v-for="element in elements" :key="element.model">
      <label :for="element.model" :class="{ required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
      <input
          v-if="['text', 'number'].indexOf(element.type) !== -1"
          :id="element.model"
          v-model="form[element.model]"
          :type="element.type"
          class="form-input w-full"
          v-bind="element.attrs"
      />
      <textarea
          v-else-if="element.type === 'textarea'"
          :id="element.model"
          v-model="form[element.model]"
          class="form-textarea w-full"
      />
      <component
          v-else
          :is="element.type"
          :id="element.model"
          :options="options[element.model] || []"
          v-model="form[element.model]"
          v-bind="element.attrs"
      />
    </div>
    <submit-button :busy="busy" @submit="submit">Änderungen speichern</submit-button>
  </div>
</template>

<script>
export default {
  name: "PageForm",
  components: { tiptap: Tiptap },
  inject: ['api'],
  props: { initData: Object },
  data () {
    return {
      form: {},
      errors: {},
      options: {},
      elements: [
        { type: 'text', model: 'title', label: 'Titel', required: true },
        { type: 'tiptap', model: 'markup', label: 'Inhalt', required: true, attrs: { 'class': 'w-full' } },
        { type: 'textarea', model: 'description', label: 'Beschreibung' },
        { type: 'textarea', model: 'keywords', label: 'Schlüsselworte' },
      ],
      busy: false
    }
  },
  watch: {
    initData: {
      handler (newValue) {
        if (newValue) {
          this.form = Object.assign({}, newValue);
        }
        else {
          this.form = {};
        }
      },
      immediate: true
    }
  },
  methods: {
    async submit () {
    }
  }
}
</script>
