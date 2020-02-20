<template>
    <form action="/" class="form-horizontal" @submit.prevent>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.article_date }" for="article_date_picker">Artikeldatum</label>
            <div class="col-3">
                <div class="col-12 input-group input-inline">
                    <datepicker id="article_date_picker" input-format="d.m.y" output-format="d.m.y" ref="dateFrom" @select="setArticleDate" @clear="setArticleDate(null)"></datepicker>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.display_from }" for="display_from_input">Anzeige von</label>
            <div class="col-3">
                <div class="col-12 input-group input-inline">
                    <input v-model="form.display_from" id="display_from_input" maxlength="10" class="form-input"><button type="button" class="btn webfont-icon-only calendarPopper btn-primary">&#xe00c;</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.display_until }" for="display_until_input">Anzeige bis</label>
            <div class="col-3">
                <div class="col-12 input-group input-inline">
                    <input v-model="form.display_until" id="display_until_input" maxlength="10" class="form-input"><button type="button" class="btn webfont-icon-only calendarPopper btn-primary">&#xe00c;</button>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.customsort }" for="custom_sort_input">generische Sortierung</label>
            <div class="col-9">
                <input v-model="form.customsort" id="custom_sort_input" maxlength="4" class="form-input col-2">
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label">Markiert</label>
            <div>
                <label class="form-switch">
                    <input v-model="form.customflags" value="1" type="checkbox">
                    <i class="form-icon"></i>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.articlecategoriesid }" for="articlecategoriesid_select"><strong>*Kategorie</strong></label>
            <div class="col-9">
                <select v-model="form.articlecategoriesid" id="articlecategoriesid_select" class="form-select">
                    <option v-for="option in options.categories" :value="option.id">{{ option.title }}</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.headline }" for="headline_input"><strong>*Überschrift/Titel</strong></label>
            <div class="col-9">
                <input v-model="form.headline" id="headline_input" maxlength="200" class="form-input">
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" for="subline_input">Unterüberschrift</label>
            <div class="col-9">
                <input v-model="form.subline" id="subline_input" maxlength="200" class="form-input">
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" for="teaser_input">Anrisstext</label>
            <div class="col-9">
                <textarea v-model="form.teaser" id="teaser_input" rows='3' class='form-input'></textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.content }" for="content_input"><strong>*Seiteninhalt</strong></label>
            <div class="col-9">
                <vue-ckeditor v-model="form.content" :config="editorConfig" id="content_input"></vue-ckeditor>
            </div>
        </div>

        <div class="divider"></div>

        <div class="form-group">
            <button type='button' @click="submit" class='btn btn-success off-3 col-6' :class="{'loading': loading}" :disabled="loading">{{ form.id ? 'Daten übernehmen' : 'Artikel anlegen' }}</button>
        </div>
    </form>
</template>
<script>

    import SimpleFetch from "../../util/simple-fetch.js";
    import VueCkeditor from "../VueCkeditor";
    import Datepicker from "../datepicker";

    export default {
        components: {
            'vue-ckeditor': VueCkeditor,
            'datepicker': Datepicker
        },

        props: {
            url: { type: String, required: true },
            initialData: { type: Object, default: () => { return {} } },
            options: { type: Object },
            editorConfig: { type: Object }
        },

        data: function() {
            return {
                form: {},
                response: {},
                loading: false
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
                this.form = Object.assign({}, this.form, newValue);
            }
        },

        mounted () {
            this.form = Object.assign({}, this.form, this.initialData);
        },

        methods: {
            async submit() {
                this.loading = true;
                this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(this.form));
                this.$emit("response-received", this.response);
                this.loading = false;
            },
            setArticleDate(date) {
                this.form.article_date = date;
            }
        }
    }
</script>