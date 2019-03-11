

    import FormPost from "./form-post.js";

    export default {
		template: '<form action="/" class="form-horizontal" @submit.prevent="submit"><div class="form-sect"><div class="form-group"><label class="form-label col-3" for="username_input"><strong>Username*</strong></label><div class="col-9"><input name="username" id="username_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="data.username"><p id="error_username" class="form-input-hint vx-error-box" :class="{ &#39;error&#39; : errors.username }">{{ errors.username }}</p></div></div><div class="form-group"><label class="form-label col-3" for="email_input"><strong>Email*</strong></label><div class="col-9"><input name="email" id="email_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="data.email"><p id="error_email" class="form-input-hint vx-error-box" :class="{ &#39;error&#39; : errors.email }">{{ errors.email }}</p></div></div><div class="form-group"><label class="form-label col-3" for="name_input"><strong>Name*</strong></label><div class="col-9"><input name="name" id="name_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="data.name"><p id="error_name" class="form-input-hint vx-error-box" :class="{ &#39;error&#39; : errors.name }">{{ errors.name }}</p></div></div><div class="form-group"><label class="form-label col-3" for="admingroupsid_select"><strong>Gruppe*</strong></label><div class="col-9"><select name="admingroupsid" id="admingroupsID_select" class="form-select" v-model="data.admingroupsid"><option v-for="option in options.admingroups" :value="option.admingroupsid">{{ option.name }}</option></select><p id="error_admingroupsid" class="form-input-hint vx-error-box" :class="{ &#39;error&#39; : errors.admingroupsid }">{{ errors.admingroupsid }}</p></div></div><div class="form-group"><label class="form-label col-3" for="pwd_input">Neues Passwort</label><div class="col-9"><input name="new_PWD" id="pwd_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="data.new_PWD"><p id="error_new_PWD" class="form-input-hint vx-error-box" :class="{ &#39;error&#39; : errors.new_PWD }">{{ errors.new_PWD }}</p></div></div><div class="form-group"><label class="form-label col-3" for="pwd2_input">Passwort wiederholen</label><div class="col-9"><input name="new_PWD_verify" id="pwd2_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="data.new_PWD_verify"><p id="error_new_PWD_verify" class="form-input-hint vx-error-box" :class="{ &#39;error&#39; : errors.new_PWD_verify }">{{ errors.new_PWD_verify }}</p></div></div><div class="divider"></div><div class="form-base"><div class="form-group off-3"><button name="submit_user" value="" type="submit" class="btn btn-success" :class="buttonClass">{{ data.id ? &#39;Daten übernehmen&#39; : &#39;User anlegen&#39; }}</button></div></div></div></form>',

        mixins: [FormPost],
        props: {
            url: { type: String, required: true },
            data: { type: Object, default: () => { return {} } },
            options: { type: Object }
        },

        data: function() {
            return {
                errors: {},
                errorMessage: "",
                buttonClass: ""
            }
        }

    }
