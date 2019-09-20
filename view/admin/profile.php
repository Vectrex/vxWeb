<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>Meine Einstellungen</h1>

<div class="form-content">

    <message-toast
        :message="toastProps.message"
        :classname="toastProps.messageClass"
        :active="toastProps.isActive"
        ref="toast"
    ></message-toast>
    <profile-form
        :url="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_post')->getUrl() ?>'"
        :initial-data="form"
        :notifications="notifications"
        @response-received="responseReceived"
        ref="form"
    ></profile-form>
</div>

<script type="module">

    import MessageToast from "/js/vue/components/message-toast.js";
    import ProfileForm from "/js/vue/components/profile-form.js";

    "use strict";

    var app = new Vue({

        components: {
            "message-toast": MessageToast,
            "profile-form": ProfileForm
        },

        el: ".form-content",

        data: {
            form: {},
            notifications: [],
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            },
        },

        methods: {
            responseReceived () {
                let response = this.$refs.form.fetch.response;
                this.toastProps = {
                    message: response.message,
                    messageClass: response.success ? 'toast-success' : 'toast-error',
                };
                this.$refs.toast.isActive = true;
            }
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
