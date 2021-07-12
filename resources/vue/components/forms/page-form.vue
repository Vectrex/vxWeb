<template>
    <form action="/" @submit.prevent>
        <div class="columns">
            <div class="column col-8">
                <div class="form-group">
                    <label class="form-label" for="alias_input">Eindeutiger Name</label>
                    <input id="alias_input" :value="form.alias" @input="form.alias = $event.target.value.toUpperCase()" class="form-input" :disabled="mode === 'edit'" maxlength="64">
                  <p v-if="errors.alias" class="form-input-hint vx-error-box error">{{ errors.alias }}</p>
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
    import VueCkeditor from "../vue-ckeditor";
    import RevisionTable from "./revision-table";

    export default {
        name: 'page-form',

        components: {
            'vue-ckeditor': VueCkeditor,
            'revision-table': RevisionTable
        },
        props: {
            mode: { type: String, default: "edit" },
            url: { type: String, required: true },
            formData: { type: Object, default: () => {{}} },
            revisionsData: { type: Array, default: () => {[]}}
        },

        data: () => ({
            form: {},
            revisions: [],
            response: {},
            loading: false,
            editorConfig: {
                allowedContent: true,
                autoParagraph: false,
                customConfig: "",
                toolbar:
                    [
                        ['Maximize', '-', 'Source'],
                        ['Undo', 'Redo', '-', 'Cut','Copy','Paste','PasteText','PasteFromWord'],
                        [ 'Find', 'Replace'],
                        [ 'Bold', 'Italic', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat', '-', 'TextColor', 'BGColor'],
                        ['NumberedList','BulletedList','-','Blockquote'],
                        ['Link','Unlink'],
                        ['Image','Table','SpecialChar'],
                        ['Styles', 'Format'],
                        ['ShowBlocks']
                    ],
                height: "24rem", contentsCss: ['/css/site.css', '/css/site_edit.css'],
                filebrowserBrowseUrl: null,
                filebrowserImageBrowseUrl: null
            }
        }),

        computed: {
            errors () {
                return this.response ? (this.response.errors || {}) : {};
            },
            message () {
                return this.response ? this.response.message : "";
            }
        },

        watch: {
            formData (newValue) {
              this.form = Object.assign({}, this.form, newValue);
            },
            revisionsData (newValue) {
              this.revisions = newValue.slice();
            }
        },

        created () {
            this.editorConfig.filebrowserBrowseUrl = this.$parent.$options.routes.fileBrowse;
            this.editorConfig.filebrowserImageBrowseUrl = this.$parent.$options.routes.fileBrowse + "?filter=image";
        },

        methods: {
            async submit() {
                this.loading = true;
                this.$emit("request-sent");
                this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
                this.loading = false;
                this.$emit("response-received", this.response);
            }
        }
    }
</script>
