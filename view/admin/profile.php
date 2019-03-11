<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>Meine Einstellungen</h1>

<div class="form-content">
    <form action="/" class="form-horizontal" v-on:submit.prevent="submit">

        <div class="form-sect">
            <div class="form-group">
                <label class="form-label col-3" for="username_input"><strong>Username*</strong></label>
                <div class="col-9">
                    <input name="username" maxlength="128" class="form-input" autocomplete="off" id="username_input" type="text" v-model="form.username">
                    <p id="error_username" class="form-input-hint vx-error-box" v-bind:class="{ 'error' : errors.username }">{{ errors.username }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="email_input"><strong>Email*</strong></label>
                <div class="col-9">
                    <input name="email" id="email_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.email">
                    <p id="error_email" class="form-input-hint vx-error-box" v-bind:class="{ 'error' : errors.email }">{{ errors.email }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="name_input"><strong>Name*</strong></label>
                <div class="col-9">
                    <input name="name" id="name_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.name">
                    <p id="error_name" class="form-input-hint vx-error-box" v-bind:class="{ 'error' : errors.name }">{{ errors.name }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="pwd_input">Neues Passwort</label>
                <div class="col-9">
                    <input name="new_PWD" id="pwd_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD">
                    <p id="error_new_PWD" class="form-input-hint vx-error-box" v-bind:class="{ 'error' : errors.new_PWD }">{{ errors.new_PWD }}</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="pwd2_input">Passwort wiederholen</label>
                <div class="col-9">
                    <input name="new_PWD_verify" id="pwd2_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD_verify">
                    <p id="error_new_PWD_verify" class="form-input-hint vx-error-box" v-bind:class="{ 'error' : errors.new_PWD_verify }">{{ errors.new_PWD_verify }}</p>
                </div>
            </div>
        </div>

        <div class="divider text-center" data-content="Benachrichtigungen"></div>

        <div class="form-sect off-3">
            <div class="form-group" v-for="notification in notifications">
                <label class="form-switch"><input name="notification[]" v-bind:value="notification.alias" type="checkbox" v-model="form.notifications"><i class="form-icon"></i>{{ notification.label }}</label>
            </div>
        </div>

        <div class="form-base">
            <div class="form-group off-3">
                <button name="submit_profile" value="" type='submit' class='btn btn-success'>Änderungen speichern</button>
            </div>
        </div>

    </form>

    <message-toast :message="message" :classname="messageClass" :state="messageState"></message-toast>

</div>

<script type="module">

    import MessageToast from "/js/vue/message-toast.js";
    import FormPost from "/js/vue/form-post.js";

    "use strict";

    Vue.component("message-toast", MessageToast);

    var app = new Vue({

        mixins: [FormPost],

        el: ".form-content",

        data: {
            form: {},
            notifications: [],
            errors: {},
            message: "",
            messageState: false,
            buttonClass: "",
            url: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_post')->getUrl() ?>"
        },

        computed: {
            messageClass: function() { return Object.keys(this.errors).length ? 'toast-error' : 'toast-success'; }
        }
    });

    fetch("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_get')->getUrl() ?>")
        .then(response => response.json())
        .then(function (data) {
            app.notifications = data.notifications;
            if (data.formData) {
                app.form = data.formData;
            }
        });

</script>