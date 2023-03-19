<script setup>
  import { Editor, EditorContent } from '@tiptap/vue-3'
  import Document from '@tiptap/extension-document'
  import Text from '@tiptap/extension-text'
  import Bold from '@tiptap/extension-bold'
  import Italic from '@tiptap/extension-italic'
  import ListItem from '@tiptap/extension-list-item'
  import BulletList from '@tiptap/extension-bullet-list'
  import OrderedList from '@tiptap/extension-ordered-list'
  import Paragraph from '@tiptap/extension-paragraph'
  import HardBreak from '@tiptap/extension-hard-break'
  import History from '@tiptap/extension-history'
  import { ArrowUturnLeftIcon, ArrowUturnRightIcon } from '@heroicons/vue/24/solid'
</script>

<template>
  <div>
    <div class="flex space-x-1 items-center justify-start rounded-t p-1 bg-slate-200">
      <button @click="editor.chain().focus().toggleBold().run()" :class="buttonClass('bold')"><strong>Ab</strong></button>
      <button @click="editor.chain().focus().toggleItalic().run()" :class="buttonClass('italic')"><em>Ab</em></button>
      <button @click="editor.commands.undo()" :class="buttonClass()"><arrow-uturn-left-icon class="h-5 w-5" /></button>
      <button @click="editor.commands.redo()" :class="buttonClass()"><arrow-uturn-right-icon class="h-5 w-5" /></button>
    </div>
    <editor-content :editor="editor" />
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
      editor: null
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
        Document,
        Text,
        Paragraph,
        HardBreak,
        History,
        BulletList,
        OrderedList,
        ListItem,
        Bold,
        Italic
      ],
      content: this.modelValue,
      onUpdate: () => this.$emit('update:modelValue', this.editor.getHTML())
    })
  },
  beforeUnmount() {
    this.editor.destroy()
  },
  methods: {
    buttonClass (isActive) {
      return 'icon-link' + (isActive && this.editor?.isActive(isActive) ? ' bg-slate-500' : '');
    }
  }
}
</script>