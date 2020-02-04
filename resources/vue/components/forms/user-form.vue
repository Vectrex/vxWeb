<template>
    <form action="/" class="form-horizontal" @submit.prevent>

        <div class="form-sect">
            <div class="form-group">
                <label class="form-label col-3" for="username_input"><strong>Username*</strong></label>
                <div class="col-9">
                    <input name="username" id="username_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.username">
                    <p v-if="errors.username" class="form-input-hint vx-error-box error">{{ errors.username }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="email_input"><strong>Email*</strong></label>
                <div class="col-9">
                    <input name="email" id="email_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.email">
                    <p v-if="errors.email" class="form-input-hint vx-error-box error">{{ errors.email }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="name_input"><strong>Name*</strong></label>
                <div class="col-9">
                    <input name="name" id="name_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.name">
                    <p v-if="errors.name" class="form-input-hint vx-error-box error">{{ errors.name }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="admingroupsid_select"><strong>Gruppe*</strong></label>
                <div class="col-9">
                    <select name="admingroupsid" id="admingroupsID_select" class="form-select" v-model="form.admingroupsid">
                        <option v-for="option in options.admingroups" :value="option.admingroupsid">
                            {{ option.name }}
                        </option>
                    </select>
                    <p v-if="errors.admingroupsid" class="form-input-hint vx-error-box error">{{ errors.admingroupsid }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-3" for="pwd_input">Neues Passwort</label>
                <div class="col-9">
                    <input name="new_PWD" id="pwd_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD">
                    <p v-if="errors.new_PWD" class="form-input-hint vx-error-box error">{{ errors.new_PWD }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-3" for="pwd2_input">Passwort wiederholen</label>
                <div class="col-9">
                    <input name="new_PWD_verify" id="pwd2_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD_verify">
                    <p v-if="errors.new_PWD_verify" class="form-input-hint vx-error-box error">{{ errors.new_PWD_verify }}</p>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="form-base">
            <div class="form-group off-3">
                <button name="submit_user" value="" type='button' class='btn btn-success' :class="{'loading': loading}" :disabled="loading" @click="submit">{{ form.id ? 'Daten übernehmen' : 'User anlegen' }}</button>
            </div>
        </div>

    </form>
</template>
<script>

    import SimpleFetch from "../../util/simple-fetch.js";

    export default {

        props: {
            url: { type: String, required: true },
            initialData: { type: Object, default: () => { return {} } },
            options: { type: Object }
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
                this.form = newValue;
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