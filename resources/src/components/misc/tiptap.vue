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
      <button @click="editor.commands.undo()" :class="buttonClass()"><arrow-uturn-left-icon class="h-5 w-5" /></button>
      <button @click="editor.commands.redo()" :class="buttonClass()"><arrow-uturn-right-icon class="h-5 w-5" /></button>
      <button @click="editor.chain().focus().toggleBold().run()" :class="buttonClass('bold')"><strong>Ab</strong></button>
      <button @click="editor.chain().focus().toggleItalic().run()" :class="buttonClass('italic')"><em>Ab</em></button>
      <button @click="editor.commands.toggleBulletList()" :class="buttonClass()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5"><path fill="none" d="M0 0h24v24H0z"/><path d="M8 4h13v2H8V4zM4.5 6.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 7a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm0 6.9a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM8 11h13v2H8v-2zm0 7h13v2H8v-2z" fill="currentColor"/></svg>
      </button>
      <button @click="editor.commands.toggleOrderedList()" :class="buttonClass()">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6"><path fill="none" d="M0 0h24v24H0z"/><path d="M8 4h13v2H8V4zM5 3v3h1v1H3V6h1V4H3V3h2zM3 14v-2.5h2V11H3v-1h3v2.5H4v.5h2v1H3zm2 5.5H3v-1h2V18H3v-1h3v4H3v-1h2v-.5zM8 11h13v2H8v-2zm0 7h13v2H8v-2z" fill="currentColor"/></svg>      </button>
    </div>
    <editor-content :editor="editor" />
    <textarea disabled class="form-textarea w-full text-sm my-2">{{ modelValue }}</textarea>
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