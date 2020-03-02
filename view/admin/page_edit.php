<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<?php $router = \vxPHP\Application\Application::getInstance()->getRouter(); ?>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

<div id="vue-root" v-cloak>
    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= $router->getRoute('pages')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>
    <div>
        <message-toast
            :message="toastProps.message"
            :classname="toastProps.messageClass"
            :active="toastProps.isActive"
            ref="toast"
        ></message-toast>
        <page-form
            :url="formUrl"
            :initial-data="{ form: formProps.form, revisions: formProps.revisions }"
            :options="formProps.options"
            @response-received="responseReceived"
            ref="form"
        ></page-form>
    </div>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>
    const MessageToast = window.vxweb.Components.MessageToast;
    const PageForm =  window.vxweb.Components.PageForm;
    const SimpleFetch =  window.vxweb.Util.SimpleFetch;

    const app = new Vue({

        components: {
            "message-toast": MessageToast,
            "page-form": PageForm
        },

        el: "#vue-root",

        data: {
            instanceId: <?= $this->id ?>,
            formProps: {
                form: {},
                revisions: [],
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
                return this.formProps.url + "?id=" + this.instanceId;
            }
        },

        routes: {
            init: "<?= $router->getRoute('page_init')->getUrl() ?>",
            update: "<?= $router->getRoute('page_update')->getUrl() ?>",
            activate: "<?= $router->getRoute('page_revision_activate')->getUrl() ?>"
        },

        async created () {
            let response = await SimpleFetch(this.$options.routes.init + "?id=" + this.instanceId);

            this.formProps = Object.assign({}, this.formProps, {
                options: response.options || {},
                form: response.form || {},
                revisions: (response.revisions || []).map(item => { item.firstCreated = new Date(item.firstCreated); return item }),
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
            }
        }
    });
</script>
