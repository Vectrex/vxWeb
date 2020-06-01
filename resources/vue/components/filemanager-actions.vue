<template>
    <div class="btn-group mx-2">
        <button class="btn webfont-icon-only tooltip" data-tooltip="Auswahl löschen" type="button" @click="$emit('delete-selection')">&#xe011;</button>
        <button class="btn webfont-icon-only tooltip" data-tooltip="Auswahl verschieben" type="button" @click="$emit('move-selection')">&#xe02a;</button>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: {
        },
        methods: {
        }
    }
</script>