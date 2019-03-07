<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>User <em class="smaller"><?= $tpl->user ? $tpl->user['name'] : 'neuer User' ?></em></h1>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="$users">Zurück zur Übersicht</a>
</div>

<div class="form-content">
    <form action="/admin/users/new" method="POST" class="form-horizontal" @submit.prevent="submit">

        <input name="_csrf_token" value="9Xw0YgzQdpNsPkCtSXbKfCzS-rgkoRkdFHBLazQzmeM" type="hidden">

        <div class="form-sect">

            <div class="form-group">
                <label class="form-label col-3" for="username_input"><strong>Username*</strong></label>
                <div class="col-9">
                    <input name="username" id="username_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.username">
                    <p id="error_username" class="form-input-hint vx-error-box" :class="{ 'error' : errors.username }">{{ errors.username }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="email_input"><strong>Email*</strong></label>
                <div class="col-9">
                    <input name="email" id="email_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.email">
                    <p id="error_email" class="form-input-hint vx-error-box" :class="{ 'error' : errors.email }">{{ errors.email }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="name_input"><strong>Name*</strong></label>
                <div class="col-9">
                    <input name="name" id="name_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.name">
                    <p id="error_name" class="form-input-hint vx-error-box" :class="{ 'error' : errors.name }">{{ errors.name }}</p>
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
                    <p id="error_admingroupsid" class="form-input-hint vx-error-box" :class="{ 'error' : errors.admingroupsid }">{{ errors.admingroupsid }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-3" for="pwd_input">Neues Passwort</label>
                <div class="col-9">
                    <input name="new_PWD" id="pwd_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD">
                    <p id="error_new_PWD" class="form-input-hint vx-error-box" :class="{ 'error' : errors.new_PWD }">{{ errors.new_PWD }}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-3" for="pwd2_input">Passwort wiederholen</label>
                <div class="col-9">
                    <input name="new_PWD_verify" id="pwd2_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD_verify">
                    <p id="error_new_PWD_verify" class="form-input-hint vx-error-box" :class="{ 'error' : errors.new_PWD_verify }">{{ errors.new_PWD_verify }}</p>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="form-base">
            <div class="form-group">
                <label class="col-3 form-label"></label><button name="submit_user" value="" type='submit' class='btn btn-success' :class=buttonClass>{{ form.id ? 'Daten übernehmen' : 'User anlegen' }}</button>
            </div>
        </div>
    </form>

    <message-toast :message="message" :classname="messageClass" :state="messageState"></message-toast>

</div>


<script>
    "use strict";

    var postData = function (url = "", data = {}) {

        return fetch(url, {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            headers: { "Content-Type": "application/json" },
            referrer: "no-referrer",
            body: JSON.stringify(data)
        })
        .then(response => response.json());

    };

    var timeoutId;

    Vue.component("message-toast", {
        data: function() {
            return {
                localState: this.state
            };
        },
        props: [
            'message',
            'classname',
            'state'
        ],

        watch: {
            state: function(newVal) {
                this.localState = newVal;

                if(timeoutId) {
                    window.clearTimeout(timeoutId);
                }
                timeoutId = window.setTimeout(() => { this.localState = false }, 5000);
            }
        },

        template: `<div id="messageBox" :class="[{ 'display': localState }, classname, 'toast']">{{ message }}<button class="btn btn-clear float-right" @click="localState = false"></button></div>`
    });

    var app = new Vue({

        el: ".form-content",

        data: {
            form: {
                id: "<?= \vxPHP\Http\Request::createFromGlobals()->query->get('id', '') ?>"
            },
            options: {
                admingroups: []
            },
            errors: {},
            message: "",
            messageState: false,
            buttonClass: ""
        },

        computed: {
            messageClass: function() { return Object.keys(this.errors).length ? 'toast-error' : 'toast-success'; }
        },

        methods: {
            submit() {
                this.buttonClass = "loading";
                this.messageState = false;

                postData("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_data_post')->getUrl() ?>", this.form)
                    .then(function(response) {

                        app.buttonClass = "";

                        if(!response.success) {
                            if(response.errors) {
                                app.errors = response.errors;
                            }
                            else {
                                app.errors = { 'generic': true };
                            }
                        }
                        else {
                            app.errors = {};

                            if(response.id) {
                                app.form.id = response.id;
                            }
                        }

                        app.messageState = !!response.message;
                        app.message = response.message;

                    });
            }
        }
    });

    fetch("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_data_get')->getUrl() ?>?id=" + app.form.id)
        .then(response => response.json())
        .then(function (data) {
            app.options.admingroups = data.options.admingroups;
            if (data.formData) {
                app.form = data.formData;
            }
        });

</script>