<template>
    <div class="mx-2" v-if="files.length || folders.length">
        <button class="btn btn-link webfont-icon-only tooltip" :data-tooltip="files.length + folders.length + ' gewählte Dateien/Ordner löschen'" type="button" @click="confirmDelete">&#xe011;</button>
        <!--
        <button class="btn webfont-icon-only tooltip" data-tooltip="Auswahl verschieben" type="button" @click="$emit('move-selection')">&#xe02a;</button>
        -->
    </div>
</template>

<script>
    export default {
        props: {
            files: { type: Array, default: [] },
            folders:  { type: Array, default: [] }
        },
        methods: {
            async confirmDelete () {
                if(await this.$parent.$refs.confirm.open('Auswahl löschen', "Selektierte Dateien/Ordner wirklich löschen?")) {
                    this.$emit('delete-selection');
                }
            }
        }
    }
</script>