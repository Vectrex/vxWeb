<template>
    <div class="mx-2" v-if="files.length || folders.length">
        <button class="btn btn-link webfont-icon-only tooltip" :data-tooltip="files.length + folders.length + ' gewählte Dateien/Ordner löschen'" type="button" @click="confirmDelete">&#xe011;</button>
        <button class="btn btn-link webfont-icon-only tooltip" :data-tooltip="files.length + folders.length + ' gewählte Dateien/Ordner verschieben'" type="button" @click="pickFolder">&#xe02d;&#xe032;&#xe00e;</button>
    </div>
</template>

<script>
    export default {
        name: 'filemanager-actions',
        emits: ['delete-selection', 'move-selection'],
        props: {
            files: { type: Array, default: () => ([]) },
            folders:  { type: Array, default: () => ([]) }
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