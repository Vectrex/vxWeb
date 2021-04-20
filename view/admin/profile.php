<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>Meine Einstellungen</h1>

<div class="form-content">
    <profile-form
        :url="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_post')->getUrl() ?>'"
        :initial-data="form"
        :notifications="notifications"
        @response-received="responseReceived"
        ref="form"
    ></profile-form>
    <message-toast v-bind="toastProps" ref="toast" @cancel="toastProps.active = false" @timeout="toastProps.active = false"></message-toast>
</div>

<script>
    const MessageToast = window.vxweb.Components.MessageToast;
    const ProfileForm =  window.vxweb.Components.ProfileForm;
    const SimpleFetch =  window.vxweb.Util.SimpleFetch;

    Vue.createApp({

        components: {
            MessageToast, ProfileForm
        },

        data: () => ({
            form: {},
            notifications: [],
            toastProps: {
                message: "",
                classname: "",
                active: false
            }
        }),

        async created () {
            let response = await SimpleFetch("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_get')->getUrl() ?>")
            this.notifications = response.notifications;
            if (response.formData) {
                this.form = response.formData;
            }
        },

        methods: {
            responseReceived () {
                let response = this.$refs.form.response;
                this.toastProps = {
                    message: response.message,
                    classname: response.success ? 'toast-success' : 'toast-error',
                    active: true
                };
            }
        }
    }).mount(".form-content");
</script>
