/* @see https://github.com/dangvanthanh/vue-ckeditor2 */
<template>
  <div class="ckeditor">
    <textarea
      :name="name"
      :id="id"
      :value="modelValue"
      :types="types"
      :config="config"
      :disabled="readOnlyMode"
    >
    </textarea>
  </div>
</template>

<script>
let inc = new Date().getTime();

export default {
  name: 'vue-ckeditor',
  emits: ['blur', 'focus', 'contentDom', 'dialogDefinition', 'fileUploadRequest', 'fileUploadResponse', 'update:modelValue'],
  props: {
    modelValue: String,
    name: { type: String, default: `editor-${++inc}` },
    id: { type: String, default:`editor-${inc}` },
    types: { type: String, default: `classic` },
    config: { type: Object, default: {} },
    instanceReadyCallback: Function,
    readOnlyMode: { type: Boolean, default: false }
  },
  data: () => ({
      instanceValue: ''
  }),
  computed: {
    instance() {
      return CKEDITOR.instances[this.id];
    }
  },
  watch: {
    value(val) {
      try {
        if (this.instance) {
          this.update(val);
        }
      } catch (e) {}
    },
    readOnlyMode(val) {
      this.instance.setReadOnly(val);
    }
  },
  mounted() {
    if (typeof CKEDITOR === 'undefined') {
      console.log('CKEDITOR is missing (http://ckeditor.com/)');
    } else {
      if (this.types === 'inline') {
        CKEDITOR.inline(this.id, this.config);
      } else {
        CKEDITOR.replace(this.id, this.config);
      }

      this.instance.setData(this.modelValue);

      this.instance.on('instanceReady', () => {
        this.instance.setData(this.modelValue);
      });

      // Ckeditor change event
      this.instance.on('change', this.onChange);

      // Ckeditor mode html or source
      this.instance.on('mode', this.onMode);

      // Ckeditor blur event
      this.instance.on('blur', evt => { this.$emit('blur', evt) });

      // Ckeditor focus event
      this.instance.on('focus', evt => { this.$emit('focus', evt) });

      // Ckeditor contentDom event
      this.instance.on('contentDom', evt => { this.$emit('contentDom', evt) });

      // Ckeditor dialog definition event
      CKEDITOR.on('dialogDefinition', evt => { this.$emit('dialogDefinition', evt) });

      // Ckeditor file upload request event
      this.instance.on('fileUploadRequest', evt => { this.$emit('fileUploadRequest', evt) });

      // Ckditor file upload response event
      this.instance.on('fileUploadResponse', evt => {
        this.$nextTick( () => { this.onChange() });
        this.$emit('fileUploadResponse', evt);
      });

      // Listen for instanceReady event
      if (typeof this.instanceReadyCallback !== 'undefined') {
        this.instance.on('instanceReady', this.instanceReadyCallback);
      }
    }
  },
  beforeUnmount() {
    try {
      let editor = window['CKEDITOR'];
      if (editor.instances && editor.instances[this.id]) {
        editor.instances[this.id].destroy();
      }
    } catch (e) {}
  },
  methods: {
    update(val) {
      if (this.instanceValue !== val) {
        this.instance.setData(val, { internal: false });
        this.instanceValue = val;
      }
    },
    onMode() {
      if (this.instance.mode === 'source') {
        let editable = this.instance.editable();
        editable.attachListener(editable, 'input', () => {
          this.onChange();
        });
      }
    },
    onChange() {
      let html = this.instance.getData();
      if (html !== this.modelValue) {
        this.$emit('update:modelValue', html);
        this.instanceValue = html;
      }
    }
  }
};
</script>
