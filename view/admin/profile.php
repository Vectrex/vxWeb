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

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>

    const MessageToast = window.vxweb.Components.MessageToast;
    const ProfileForm =  window.vxweb.Components.ProfileForm;
    const SimpleFetch =  window.vxweb.Components.SimpleFetch;

    const app = new Vue({

        el: ".form-content",

        components: {
            MessageToast, ProfileForm
        },

        data: {
            form: {},
            notifications: [],
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            }
        },

        mounted () {
            SimpleFetch("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_get')->getUrl() ?>")
                .then(response => {
                    this.notifications = response.notifications;
                    if (response.formData) {
                        this.form = response.formData;
                    }
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
            }
        }
    });

</script>
