<script setup>
  import { DocumentMinusIcon, DocumentPlusIcon, PlayIcon, TrashIcon } from '@heroicons/vue/24/solid';
</script>
<template>
    <div class="flex items-center space-x-2">
      <button
        class="icon-link flex items-center tooltip"
        :data-tooltip="files.length + folders.length + ' gewählte Dateien/Ordner verschieben'"
        type="button"
        @click="pickFolder"
      >
        <document-minus-icon class="h-5 w-5"/>
        <play-icon class="h-3 w-3" />
        <document-plus-icon class="h-5 w-5"/>
      </button>
      <button
        class="icon-link tooltip"
        :data-tooltip="files.length + folders.length + ' gewählte Dateien/Ordner löschen'"
        type="button"
        @click="confirmDelete"
      >
        <trash-icon class="h-5 w-5" />
      </button>
    </div>
</template>

<script>
    export default {
        name: 'FilemanagerActions',
        emits: ['delete-selection', 'move-selection'],
        props: {
            files: Array,
            folders: Array
        },
        methods: {
            async confirmDelete () {
                if(await this.$parent.$refs.confirm.open('Auswahl löschen', "Selektierte Dateien/Ordner wirklich löschen?")) {
                    this.$emit('delete-selection');
                }
            },
            async pickFolder () {
              this.$emit('move-selection');
            }
        }
    }
</script>