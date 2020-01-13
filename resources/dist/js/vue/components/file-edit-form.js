
    import SimpleFetch from "../util/simple-fetch.js";

    export default {
		template: '<form action="/" id="events-edit-form" @submit.prevent=""><div class="form-group"><label for="title_input">Titel</label><input id="title_input" class="form-input" v-model="form.title" autocomplete="off" :class="{&#39;is-error&#39;: errors.title}"></div><div class="form-group"><label for="subtitle_input">Untertitel</label><input id="subtitle_input" class="form-input" v-model="form.subtitle" autocomplete="off" :class="{&#39;is-error&#39;: errors.subtitle}"></div><div class="form-group"><label for="description_input">Beschreibung</label><textarea rows="2" id="description_input" class="form-input" v-model="form.description" :class="{&#39;is-error&#39;: errors.description}"></textarea></div><div class="divider"></div><div class="form-group"><button type="button" @click="submit" class="btn btn-success col-12" :class="{&#39;loading&#39;: loading}" :disabled="loading">Änderungen speichern</button></div></form>',
        props: {
            initialData: { type: Object, default: () => { return {} } },
            url: { type: String, default: "" }
        },
        data() {
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
        methods: {
            async submit() {
                this.loading = true;

                /* avoid strings "null" with null values */

                let formData = {};

                Object.keys(this.form).forEach(key => { if(this.form[key] !== null) { formData[key] = this.form[key]; }});

                this.response = await SimpleFetch(this.url, 'POST', {}, JSON.stringify(formData));
                this.$emit('response-received');
                this.loading = false;
            }
        }
    }
