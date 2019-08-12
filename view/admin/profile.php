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
            @form-response-received="responseReceived"
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


            searchTerm: null,

            searchOptions: [
                { id: 1, title: "baz bar" },
                { id: 2, title: "foo bar" },
                {
                    id: 3,
                    title: "Eos rerum veniam quia mollitia quod et et accusamus." },

                { id: 4, title: "Robs THread" },
                { id: 5, title: "test" },
                { id: 6, title: "goose" },
                { id: 7, title: "loose goose" },
                { id: 8, title: "geese" },
                { id: 9, title: "moose" },
                { id: 10, title: "test thread updated" },
                {
                    id: 11,
                    title:
                        "Distinctio quo praesentium quis commodi praesentium excepturi." },

                { id: 12, title: "changed new thread" },
                { id: 13, title: "fred" },
                { id: 14, title: "barney" }
            ]


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

    fetch("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('profile_data_get')->getUrl() ?>")
        .then(response => response.json())
        .then(function (data) {
            app.notifications = data.notifications;
            if (data.formData) {
                app.form = data.formData;
            }
        });

</script>
