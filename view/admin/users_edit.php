<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="vue-root">

    <h1>User <em class="smaller"><?= htmlspecialchars($this->user['name'] ?? 'neuer User') ?></em></h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('users')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>

    <div class="form-content">
        <message-toast
            :message="toastProps.message"
            :classname="toastProps.messageClass"
            :active="toastProps.isActive"
            ref="toast"
        ></message-toast>
        <user-form
            :url="formProps.url"
            :initial-data="formProps.form"
            :options="formProps.options"
            @response-received="responseReceived"
            ref="form"
        ></user-form>
    </div>

</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>

    const MessageToast = window.vxweb.Components.MessageToast;
    const UserForm =  window.vxweb.Components.UserForm;
    const SimpleFetch =  window.vxweb.Components.SimpleFetch;

    const app = new Vue({

        components: {
            "message-toast": MessageToast,
            "user-form": UserForm
        },

        el: "#vue-root",

        data: {
            instanceId: <?= $this->user['id'] ?? 'null' ?>,
            formProps: {
                form: {},
                url: "",
                options: {}
            },
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            }
        },

        computed: {
            formUrl () {
                if(!this.instanceId) {
                    return this.formProps.url;
                }
                return this.formProps.url + "?id=" + this.instanceId;
            }
        },

        routes: {
            init: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_init')->getUrl() ?>",
            update: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_update')->getUrl() ?>"
        },

        async created () {
            let response = await SimpleFetch(this.$options.routes.init + "?id=" + (this.instanceId || ''));

            this.formProps = Object.assign({}, this.formProps, {
                options: response.options || {},
                form: response.form || {},
                url: this.$options.routes.update
            });
        },

        methods: {
            responseReceived () {
                let response = this.$refs.form.response;
                this.toastProps = {
                    message: response.message,
                    messageClass: response.success ? 'toast-success' : 'toast-error',
                };
                this.$refs.toast.isActive = true;
                if(response.instanceId) {
                    this.instanceId = response.instanceId;
                    this.formProps.form = Object.assign({}, this.formProps.form, { id: response.instanceId });
                }
            }
        }
    });
</script>