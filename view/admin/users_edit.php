<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="vue-root">

    <h1>User <em class="smaller"><?= $tpl->user ? $tpl->user['name'] : 'neuer User' ?></em></h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="$users">Zurück zur Übersicht</a>
    </div>

    <div class="form-content">
        <message-toast
            :message="toastProps.message"
            :classname="toastProps.messageClass"
            :active="toastProps.isActive"
            ref="toast"
        ></message-toast>
        <user-form
            :url="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_data_post')->getUrl() ?>'"
            :initial-data="form"
            :options="options"
            @form-response-received="responseReceived"
        ></user-form>
    </div>

</div>

    <script type="module">

    import MessageToast from "/js/vue/message-toast.js";
    import FormPost from "/js/vue/form-post.js";
    import UserForm from "/js/vue/user-form.js";

    "use strict";

    var app = new Vue({

        components: {
            "message-toast": MessageToast,
            "user-form": UserForm
        },

        el: "#vue-root",

        data: {
            form: {
                id: "<?= \vxPHP\Http\Request::createFromGlobals()->query->get('id', '') ?>"
            },
            options: {
                admingroups: []
            },
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            }
        },

        methods: {
            responseReceived (response) {
                this.toastProps = {
                    message: response.message,
                    messageClass: response.success ? 'toast-success' : 'toast-error',
                };
                this.$refs.toast.isActive = true;
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