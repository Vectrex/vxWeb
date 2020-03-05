<template>
    <form action="/" @submit.prevent>
        <div class="columns">
            <div class="column col-8">
                <div class="form-group">
                    <label class="form-label" for="alias_input">Eindeutiger Name (automatisch generiert)</label>
                    <input id="alias_input" v-model="form.alias" class="form-input" disabled="disabled" maxlength="64">
                </div>
                <div class="form-group">
                    <label class="form-label" for="title_input">Titel</label>
                    <input id="title_input" v-model="form.title" class="form-input" maxlength="128">
                    <p v-if="errors.title" class="form-input-hint vx-error-box error">{{ errors.title }}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Inhalt</label>
                    <vue-ckeditor v-model="form.markup" :config="editorConfig"></vue-ckeditor>
                </div>
            </div>
            <div class="column col-4">
                <div class="form-group">
                    <label class="form-label" for="keywords_input">Schlüsselwörter</label>
                    <textarea id="keywords_input" class="form-input" rows="4" v-model="form.keywords"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="description_input">Beschreibung</label>
                    <textarea id="description_input" class="form-input" rows="4" v-model="form.description"></textarea>
                </div>
                <div class="divider" data-content="Revisionen"></div>
                <div id="revisionsContainer">
                    <revision-table
                        :revisions="revisions"
                        @activate-revision="$emit('activate-revision', $event)"
                        @load-revision="$emit('load-revision', $event)"
                        @delete-revision="$emit('delete-revision', $event)"
                    ></revision-table>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="form-base">
            <div class="form-group">
                <button name="submit_page" type='button' class='btn btn-success' :class="{'loading': loading}" :disabled="loading" @click="submit">Änderungen übernehmen und neue Revision erzeugen</button>
            </div>
        </div>
    </form>
</template>

<script>
    import SimpleFetch from "../../util/simple-fetch.js";
    import VueCkeditor from "../VueCkeditor";
    import RevisionTable from "../revision-table";

    export default {
        components: {
            'vue-ckeditor': VueCkeditor,
            'revision-table': RevisionTable
        },
        props: {
            url: { type: String, required: true },
            initialData: { type: Object, default: () => { return {} } }
        },

        data() {
            return {
                form: {},
                revisions: [],
                response: {},
                loading: false,
                editorConfig: {
                    toolbar:
                        [
                            ['Maximize', '-', 'Source', '-', 'Undo', 'Redo'],
                            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
                            ['Bold', 'Italic', 'Superscript', 'Subscript', '-', 'CopyFormatting', 'RemoveFormat'],
                            ['NumberedList', 'BulletedList'],
                            ['Link', 'Unlink'], ['Format'],
                            ['ShowBlocks']
                        ],
                    height: "20rem",
                    format_tags: "h1;h2;p",
                    format_p: {element: "p"},
                    format_h1: {element: "h2"},
                    format_h2: {element: "h3"},
                    heading: {
                        options: [
                            {model: 'paragraph', title: 'Absatz'},
                            {model: 'heading1', view: 'h3', title: 'Überschrift 1', class: 'h3'},
                            {model: 'heading2', view: 'h4', title: 'Überschrift 2', class: 'h4'}
                        ]
                    }
                }
            }
        },

        computed: {
            errors () {
                return this.response ? (this.response.errors || {}) : {};
            },
            message () {
                return this.response ? this.response.message : "";
            }
        },

        watch: {
            initialData (newValue) {
                this.form = newValue.form || this.form;
                this.revisions = newValue.revisions || this.revisions;
            }
        },

        methods: {
            async submit() {
                this.loading = true;
                this.$emit("request-sent");
                this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
                this.loading = false;
                this.$emit("response-received");
            }
        }
    }
</script>
