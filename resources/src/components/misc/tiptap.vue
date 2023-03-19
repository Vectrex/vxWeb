<script setup>
  import { Editor, EditorContent } from '@tiptap/vue-3'
  import StarterKit from '@tiptap/starter-kit'
</script>

<template>
  <div>
    <div class="flex space-x-1 items-center justify-start rounded-t p-1 bg-slate-200">
      <button @click="editor.chain().focus().toggleBold().run()" :class="['icon-link font-bold w-12', { 'bg-slate-400': editor?.isActive('bold') }]">Ab</button>
      <button @click="editor.chain().focus().toggleItalic().run()" :class="['icon-link italic w-12', { 'bg-slate-400': editor?.isActive('italic') }]">Ab</button>
    </div>
    <editor-content :editor="editor" class="border-slate-300 border-2 p-2" />
  </div>
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
  mounted () {
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