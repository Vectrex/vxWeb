<template>
    <form action="/" class="form-horizontal" @submit.prevent>

        <div class="form-sect">
            <div v-for="element in elements" class="form-group">
                <label class="form-label col-3" :for="element.model + '_' + element.type" :class=" { required: element.required, 'text-error': errors[element.model] }">{{ element.label }}</label>
                <div class="col-9">
                    <component
                        :is="element.type || 'form-input'"
                        :id="element.model + '_' + element.type"
                        :name="element.model"
                        v-model="form[element.model]"
                    >
                    </component>
                    <p v-if="errors[element.model]" class="form-input-hint vx-error-box error">{{ errors[element.model] }}</p>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="form-base">
            <div class="form-group off-3">
                <button name="submit_user" type='button' class='btn btn-success' :class="{'loading': loading}" :disabled="loading" @click="submit">{{ form.id ? 'Daten übernehmen' : 'User anlegen' }}</button>
            </div>
        </div>

    </form>
</template>
<script>

    import SimpleFetch from "../../util/simple-fetch.js";
    import PasswordInput from "../formelements/password-input";
    import FormInput from "../formelements/form-input";
    import FormTextarea from "../formelements/form-textarea";
    import FormSelect from "../formelements/form-select";

    export default {
        components: {
            'password-input': PasswordInput,
            'form-input': FormInput,
            'form-textarea': FormTextarea,
            'form-select': FormSelect
        },

        props: {
            url: { type: String, required: true },
            initialData: { type: Object, default: () => { return {} } },
            options: { type: Object }
        },

        data: function() {
            return {
                form: {},
                response: {},
                loading: false,
                elements: [
                    { model: 'username', label: 'Username', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
                    { model: 'email', label: 'E-Mail', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
                    { model: 'name', label: 'Name', attrs: { maxlength: 128, autocomplete: "off" }, required: true },
                    { type: 'password-input', model: 'new_PWD', label: 'Neues Passwort', attrs: { maxlength: 128, autocomplete: "off" } },
                    { type: 'password-input', model: 'new_PWD_verify', label: 'Passwort wiederholen', attrs: { maxlength: 128, autocomplete: "off" } },
                    { type: 'form-select', model: 'admingroupsid', label: 'Gruppe', required: true, attrs: { options: [] } }
                ]
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
                this.form = newValue;
            },
            options (newValue) {
              this.elements[this.elements.findIndex(item => item.model === 'admingroupsid')].attrs.options = newValue.admingroups;
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