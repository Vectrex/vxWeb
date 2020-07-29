<template>
    <form action="/" class="form-horizontal" @submit.prevent>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.article_date }" for="article_date_picker">Artikeldatum</label>
            <div class="col-3">
                <div class="col-12 input-group input-inline">
                    <datepicker
                        id="article_date_picker"
                        input-format="d.m.y"
                        output-format="d.m.y"
                        ref="dateArticle"
                        v-model="form.article_date"
                        :day-names="'Mo Di Mi Do Fr Sa So'.split(' ')"
                        :month-names="'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' ')"
                    ></datepicker>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.display_from }" for="display_from_picker">Anzeige von</label>
            <div class="col-3">
                <div class="col-12 input-group input-inline">
                    <datepicker
                        id="display_from_picker"
                        input-format="d.m.y"
                        output-format="d.m.y"
                        ref="dateFrom"
                        v-model="form.display_from"
                        :day-names="'Mo Di Mi Do Fr Sa So'.split(' ')"
                        :month-names="'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' ')"
                        :valid-from="new Date()"
                    ></datepicker>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-3 form-label" :class="{ 'text-error': errors.display_until }" for="display_until_picker">Anzeige bis</label>
            <div class="col-3">
                <div class="col-12 input-group input-inline">
                    <datepicker
                            id="display_until_picker"
                            input-format="d.m.y"
                            output-format="d.m.y"
                            ref="dateFrom"
                            v-model="form.display_until"
                            :day-names="'Mo Di Mi Do Fr Sa So'.split(' ')"
                            :month-names="'Jan Feb Mär Apr Mai Jun Jul Aug Sep Okt Nov Dez'.split(' ')"
                            :valid-from="new Date()"
                    ></datepicker>
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
            <button type='button' @click="submit" class='btn btn-success off-3 col-3' :class="{'loading': loading}" :disabled="loading">{{ form.id ? 'Daten übernehmen' : 'Artikel anlegen' }}</button>
        </div>
    </form>
</template>
<script>

    import SimpleFetch from "../../util/simple-fetch.js";
    import VueCkeditor from "../VueCkeditor";
    import Datepicker from "../formelements/datepicker";

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
                this.setDates();
            }
        },

        mounted () {
            this.form = Object.assign({}, this.form, this.initialData);
            this.setDates();
        },

        methods: {
            async submit () {
                this.loading = true;
                let payload = Object.assign({}, this.form);
                Object.keys(payload).forEach(prop => {
                    if(payload[prop] instanceof Date) {
                        payload[prop] = payload[prop].getFullYear() + '-' + (payload[prop].getMonth() + 1) + '-' + payload[prop].getDate();
                    }
                });
                this.response = await SimpleFetch(this.url, 'post', {}, JSON.stringify(payload));
                if(this.response.id) {
                    this.$set(this.form, 'id', this.response.id);
                }
                this.$emit("response-received", this.response);
                this.loading = false;
            },
            setDates () {
                ['article_date', 'display_from', 'display_until'].forEach(item => {
                    if (this.form[item] && !(this.form[item] instanceof Date)) {
                        this.form[item] = new Date(this.form[item]);
                    }
                });
            }
        }
    }
</script>