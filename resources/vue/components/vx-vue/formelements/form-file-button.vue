<template>
    <label v-bind="$attrs" :for="id">
      {{ label }}
      <input
          type="file"
          :id="id"
          :multiple="multiple"
          :accept="accept"
          @change="fileChanged"
          class="d-none"
      />
    </label>
</template>

<script>
export default {

  inheritAttrs: false,

  props: {
    value: { type: Array },
    accept: { type: String, default: "*" },
    multiple: { type: Boolean, default: false },
    name: { type: String, default: "file" },
    label: { type: String, default: 'Upload' },
    id: { type: String, default: 'file_upload' }
  },

  data: () => {
    return {
      filename: ""
    };
  },

  watch: {
    value(v) {
      this.filename = v;
    }
  },

  mounted() {
    this.filename = this.value;
  },

  methods: {
    getFormData(files) {
      const data = new FormData();
      const name = this.name + (this.multiple ? "[]" : "");
      for (let file of files) {
        data.append(name, file, file.name);
      }
      return data;
    },
    fileChanged (event) {
      let files = event.target.files || event.dataTransfer.files;
      if (files) {

        // convert FileList to Array

        files = [...files];
        this.$emit('input', files);
        this.$emit('form-data', this.getFormData(files));
      }
    }
  }
};
</script>
