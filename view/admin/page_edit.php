<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<?php $router = \vxPHP\Application\Application::getInstance()->getRouter(); ?>

<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

<div id="app" v-cloak>
    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= $router->getRoute('pages')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>
    <page-form
        :url="formUrl"
        :form-data="formProps.form"
        :revisions-data="formProps.revisions"
        :mode="instanceId ? 'edit': 'add'"
        @response-received="responseReceived"
        @activate-revision="activateRevision"
        @load-revision="loadRevision"
        @delete-revision="deleteRevision"
        ref="form"
    ></page-form>
    <message-toast v-bind="toastProps" ref="toast" @cancel="toastProps.active = false" @timeout="toastProps.active = false"></message-toast>
    <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
</div>

<script>
    const { MessageToast, PageForm, Confirm } = window.vxweb.Components;
    const { SimpleFetch, UrlQuery } =  window.vxweb.Util;

    Vue.createApp({

        components: { "message-toast": MessageToast, "page-form": PageForm, "confirm": Confirm },

        data: () => ({
            instanceId: <?= $this->id ?? 'null' ?>,
            formProps: {
                form: {},
                revisions: [],
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
                if (this.instanceId) {
                    return this.formProps.url + "?id=" + this.instanceId;
                }
                return this.formProps.url;
            }
        },

        routes: {
            init: "<?= $router->getRoute('page_init')->getUrl() ?>",
            update: "<?= $router->getRoute('page_update')->getUrl() ?>",
            load: "<?= $router->getRoute('page_revision_load')->getUrl() ?>",
            activate: "<?= $router->getRoute('page_revision_activate')->getUrl() ?>",
            delete: "<?= $router->getRoute('page_revision_delete')->getUrl() ?>",
            fileBrowse: "<?= $router->getRoute('filepicker')->getUrl() ?>"
        },

        async created () {
            this.formProps.url = this.$options.routes.update;

            if(this.instanceId) {
                let response = await SimpleFetch(this.$options.routes.init + "?id=" + this.instanceId);

                this.formProps = Object.assign({}, this.formProps, {
                    options: response.options || {},
                    form: response.form || {},
                    revisions: (response.revisions || []).map(item => {
                        item.firstCreated = new Date(item.firstCreated);
                        return item
                    })
                });
            }
        },

        methods: {
            responseReceived (response) {

                // switch from add to edit

                if(response.instanceId) {
                    this.instanceId = response.instanceId;
                }
                this.toastProps = {
                    message: response.message,
                    classname: response.success ? 'toast-success' : 'toast-error',
                    active: true
                };
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
                    this.formProps.form = response.form || {};
                }
            },
            async loadRevision (rev) {
                let response = await SimpleFetch(UrlQuery.create(this.$options.routes.load, { id: rev.id }));
                if(response.success) {
                    this.formProps.form = response.form || {};
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
    }).mount('#app');
</script>
