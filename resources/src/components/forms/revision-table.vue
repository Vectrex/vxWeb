<template>
    <table id="revisions" class="table table-striped">
        <thead>
        <tr>
            <th>Angelegt um</th>
            <th class="col-2"></th>
            <th class="col-2">aktiv</th>
            <th class="col-2"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="revision in sortedRevisions" :key="revision.id">
            <td>{{ formatDateTime(revision.firstCreated, 'y-mm-dd h:i:s') }}</td>
            <td>
                <button class="btn btn-link webfont-icon-only tooltip" type="button" data-tooltip="Ansicht" @click="$emit('load-revision', revision)">&#xe015;</button>
            </td>
            <td>
                <label class="form-switch">
                    <input type="checkbox" :checked="revision.active" :disabled="revision.active" @click="$emit('activate-revision', revision)">
                    <i class="form-icon"></i>
                </label>
            </td>
            <td class="text-right">
                <button class="btn btn-primary webfont-icon-only tooltip tooltip-left" type="button" data-tooltip="Löschen" @click="$emit('delete-revision', revision)" v-if="!revision.active">&#xe011;</button>
            </td>
        </tr>
        </tbody>
    </table>
</template>
<script>
    import DateFunctions from '../../../vue/util/date-functions';
    export default {
        props: {
            revisions: { type: Array, default: [] }
        },
        computed: {
            sortedRevisions() {
                return this.revisions.slice().sort((a, b) => a.firstCreated < b.firstCreated ? 1 : -1);
            }
        },
        methods: {
            formatDateTime: DateFunctions.formatDate
        }

    }
</script>
