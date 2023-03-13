<script setup>
  import { Editor, EditorContent } from '@tiptap/vue-3'
  import StarterKit from '@tiptap/starter-kit'
</script>

<template>
  <div class="flex">
    <button @click="editor.chain().focus().toggleBold().run()" :class="{ 'text-red-500': editor.isActive('bold') }">bold</button>
  </div>
  <editor-content :editor="editor" />
</template>

<script>
export default {
  name: 'tiptap',
  props: {
    modelValue: String
  },
  emits: ['update:modelValue'],
  data() {
    return {
      editor: null,
    }
  },
  watch: {
    modelValue (newValue) {
      if (this.editor.getHTML() !== newValue) {
        this.editor.commands.setContent (newValue, false);
      }
    }
  },
  mounted() {
    this.editor = new Editor({
      extensions: [
        StarterKit,
      ],
      content: this.modelValue,
      onUpdate: () => this.$emit('update:modelValue', this.editor.getHTML())
    })
  },
  beforeUnmount() {
    this.editor.destroy()
  },
}
</script>