<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<?php $router = \vxPHP\Application\Application::getInstance()->getRouter(); ?>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

<div id="vue-root" v-cloak>
    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= $router->getRoute('pages')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>
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
        @activate-revision="activateRevision"
        @load-revision="loadRevision"
        @delete-revision="deleteRevision"
        ref="form"
    ></page-form>
    <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>
    const { MessageToast, PageForm, Confirm } = window.vxweb.Components;
    const { SimpleFetch, UrlQuery } =  window.vxweb.Util;

    const app = new Vue({

        components: { "message-toast": MessageToast, "page-form": PageForm, "confirm": Confirm },

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
            load: "<?= $router->getRoute('page_revision_load')->getUrl() ?>",
            activate: "<?= $router->getRoute('page_revision_activate')->getUrl() ?>",
            delete: "<?= $router->getRoute('page_revision_delete')->getUrl() ?>",
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
                if(response.success) {
                    if (response.revisions) {
                        this.formProps.revisions = response.revisions.map(item => { item.firstCreated = new Date(item.firstCreated); return item });
                    }
                }
            },
            async activateRevision (rev) {
                let response = await SimpleFetch(UrlQuery.create(this.$options.routes.activate, { id: rev.id }), 'POST');
                if(response.success) {
                    this.formProps.revisions = (response.revisions || []).map(item => { item.firstCreated = new Date(item.firstCreated); return item }),
                    this.formProps.form = response.form || {}
                }
            },
            async loadRevision (rev) {
                let response = await SimpleFetch(UrlQuery.create(this.$options.routes.load, { id: rev.id }));
                if(response.success) {
                    this.formProps.form = response.form || {}
                }
            },
            async deleteRevision (rev) {
                if(await this.$refs.confirm.open('Revision löschen', "Soll die Revision wirklich gelöscht werden?")) {
                    let response = await SimpleFetch(UrlQuery.create(this.$options.routes.delete, { id: rev.id }), 'DELETE');
                    if(response.success) {
                        this.formProps.revisions = response.revisions.map(item => { item.firstCreated = new Date(item.firstCreated); return item });
                    }
                }
            }
        }
    });
</script>
