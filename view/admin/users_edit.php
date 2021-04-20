<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>User <em class="smaller"><?= htmlspecialchars($this->user['name'] ?? 'neuer User') ?></em></h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('users')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>

    <div class="form-content">
        <user-form
            :url="formProps.url"
            :initial-data="formProps.form"
            :options="formProps.options"
            @response-received="responseReceived"
            ref="form"
        ></user-form>
        <message-toast v-bind="toastProps" ref="toast" @cancel="toastProps.active = false" @timeout="toastProps.active = false"></message-toast>
    </div>

</div>

<script>
    const MessageToast = window.vxweb.Components.MessageToast;
    const UserForm =  window.vxweb.Components.UserForm;
    const SimpleFetch =  window.vxweb.Util.SimpleFetch;

    Vue.createApp({

        components: {
            "message-toast": MessageToast,
            "user-form": UserForm
        },

        data: () => ({
            instanceId: <?= $this->user['id'] ?? 'null' ?>,
            formProps: {
                form: {},
                url: "",
                options: {}
            },
            toastProps: {
                message: "",
                classname: "",
                active: false
            }
        }),

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

            this.formProps = {
                options: response.options || {},
                form: response.form || {},
                url: this.$options.routes.update
            };
        },

        methods: {
            responseReceived () {
                let response = this.$refs.form.response;
                this.toastProps = {
                    message: response.message,
                    classname: response.success ? 'toast-success' : 'toast-error',
                    active: true
                };
                if(response.instanceId) {
                    this.instanceId = response.instanceId;
                    this.formProps.form = Object.assign({}, this.formProps.form, { id: response.instanceId });
                }
            }
        }
    }).mount('#app');
</script>