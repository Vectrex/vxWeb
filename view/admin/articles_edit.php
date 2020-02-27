<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<?php
    function toBytes ($val)
    {
        $prefix = strtolower(substr(trim($val),-1));
        $val = (int) $val;
        switch($prefix) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    $router = \vxPHP\Application\Application::getInstance()->getRouter();

    $uploadMaxFilesize = min(
        toBytes(ini_get('upload_max_filesize')),
        toBytes(ini_get('post_max_size'))
    );
    $maxExecutionTime = ini_get('max_execution_time');
?>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

<div id="vue-root" v-cloak>

    <h1>Artikel &amp; News <em class="text-smaller">{{ formProps.form.headline }}</em></h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= $router->getRoute('articles')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>

    <message-toast
            :message="toastProps.message"
            :classname="toastProps.messageClass"
            :active="toastProps.isActive"
            ref="toast"
    ></message-toast>

    <tab :items="tabItems" :active-index.sync="activeTabIndex"></tab>

    <section id="article-form" v-if="activeTabIndex === 0" class="form-content">
        <article-form :url="formProps.url" :options="formProps.options" :initial-data="formProps.form" :editor-config="editorConfig" @response-received="handleResponse"></article-form>
    </section>

    <section id="article-files" v-if="activeTabIndex === 1">
        <filemanager
            :routes="fmRoutes"
            :columns="fmProps.cols"
            :init-sort="fmProps.initSort"
            ref="fm"
            @response-received="handleResponse"
            @after-sort="storeSort"
        >
            <template v-slot:action="slotProps">
                <button v-if="slotProps.row.isFolder" class="btn webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="$refs.fm.delFolder(slotProps.row)">&#xe008;</button>
                <template v-else>
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFile(slotProps.row)">&#xe002;</button>
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="$refs.fm.getFolderTree(slotProps.row)">&#xe004;</button>
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="$refs.fm.delFile(slotProps.row)">&#xe011;</button>
                </template>
            </template>
            <template v-slot:linked="slotProps">
                <label class="form-checkbox" v-if="!slotProps.row.isFolder"><input type="checkbox" @click="handleLink(slotProps.row)" :checked="slotProps.row.linked"><i class="form-icon"></i></label>
            </template>
            <template v-slot:linked-header="slotProps">&#128279;</template>
        </filemanager>
    </section>

    <section id="article-files-sort" v-if="activeTabIndex === 2">
        <sortable-list v-model="fmProps.cols" lock-axis="y" helper-class="slick-sort-helper" press-delay="200">
            <sortable-item v-for="(item, ndx) in fmProps.cols" :index="ndx" :key="ndx">
                <span>{{ item.label }}</span> <a :href="'https://google.com'">Link</a>
            </sortable-item>
        </sortable-list>
    </section>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>
    const components = window.vxweb.Components, mixins = window.vxweb.Mixins;
    const MessageToast = components.MessageToast;
    const Tab = components.Tab;
    const Filemanager = components.Filemanager;
    const SimpleFetch =  components.SimpleFetch;
    const ArticleForm = components.ArticleForm;

    const SortableList = {
        mixins: [mixins.ContainerMixin], template: '<div><slot /></div>'
    };

    const SortableItem = {
        mixins: [mixins.ElementMixin], props: ['item'], template: '<div class="slick-sort-item"><slot>{{ item }}</slot></div>'
    };

    Vue.component('z-link', components.ZLink);

    const app = new Vue({

        el: '#vue-root',

        components: {
            "message-toast": MessageToast,
            "tab": Tab,
            "filemanager": Filemanager,
            "article-form": ArticleForm,
            "sortable-list": SortableList,
            "sortable-item": SortableItem
        },

        computed: {
            fmRoutes () {
                return {
                    init: "<?= $router->getRoute('article_files_init')->getUrl() ?>?article=" + this.instanceId,
                    uploadFile: "<?= $router->getRoute('article_file_upload')->getUrl() ?>?article=" + this.instanceId,
                    readFolder: "<?= $router->getRoute('article_folder_read')->getUrl() ?>?article=" + this.instanceId,
                    getFile: "<?= $router->getRoute('file_get')->getUrl() ?>",
                    updateFile: "<?= $router->getRoute('file_update')->getUrl() ?>",
                    delFile: "<?= $router->getRoute('file_del')->getUrl() ?>",
                    renameFile: "<?= $router->getRoute('file_rename')->getUrl() ?>",
                    moveFile: "<?= $router->getRoute('file_move')->getUrl() ?>",
                    getFoldersTree: "<?= $router->getRoute('folders_tree')->getUrl() ?>",
                    delFolder: "<?= $router->getRoute('folder_del')->getUrl() ?>",
                    renameFolder: "<?= $router->getRoute('folder_rename')->getUrl() ?>",
                    addFolder: "<?= $router->getRoute('folder_add')->getUrl() ?>"
                }
            }
        },

        data: {
            instanceId: <?= isset($this->article) ? $this->article->getId() : 'null' ?>,
            activeTabIndex: 0,
            tabItems: [
                { name: 'Inhalt' },
                { name: 'Dateien' },
                { name: 'Sortierung' }
            ],
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            },
            formProps: {
                url: "<?= $router->getRoute('article_update')->getUrl() ?>",
                options: {},
                form: {}
            },
            fmProps: {
                cols: [
                    {
                        label: "Dateiname",
                        sortable: true,
                        prop: "name",
                        sortAscFunction: (a, b) => {
                            if (a.isFolder && !b.isFolder) {
                                return -1;
                            }
                            return a.name.toLowerCase() === b.name.toLowerCase() ? 0 : a.name.toLowerCase() < b.name.toLowerCase() ? -1 : 1;
                        },
                        sortDescFunction: (a, b) => {
                            if (a.isFolder && !b.isFolder) {
                                return -1;
                            }
                            return a.name.toLowerCase() === b.name.toLowerCase() ? 0 : a.name.toLowerCase() < b.name.toLowerCase() ? 1 : -1;
                        }
                    },
                    { label: "Link", sortable: true, prop: "linked" },
                    { label: "Größe", sortable: true, prop: "size" },
                    { label: "Typ/Vorschau", sortable: true, prop: "type" },
                    { label: "Erstellt", sortable: true, prop: "modified" },
                    { label: "", prop: "action" }
                ],
                initSort: {}
            },
            editorConfig: {
                extraAllowedContent: "div(*)",
                customConfig: "",
                toolbar:
                    [
                        ['Maximize','-','Source', '-', 'Undo','Redo'],
                        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
                        ['Bold', 'Italic', 'Superscript', 'Subscript', '-', 'CopyFormatting', 'RemoveFormat'],
                        ['NumberedList','BulletedList'],
                        ['Link', 'Unlink'],
                        ['Table'],
                        ['ShowBlocks']
                    ], height: "20rem", contentsCss: ['/css/site.css', '/css/site_edit.css']
            }
        },

        watch: {
            instanceId (newValue) {
                if(newValue) {
                    this.tabItems.forEach((item, ndx) => item.disabled = !this.instanceId && ndx !== 0);
                }
            }
        },

        routes: {
            init: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_init')->getUrl() ?>",
            link: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_link_file')->getUrl() ?>"
        },

        async created () {
            let response = await SimpleFetch(this.$options.routes.init + "?id=" + (this.instanceId || ''));

            this.tabItems.forEach((item, ndx) => item.disabled = !this.instanceId && ndx !== 0);

            this.formProps = Object.assign({}, this.formProps, {
                options: response.options || {},
                form: response.form || {}
            });

            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/files__sort__");
            if(lsValue) {
                this.fmProps.initSort = JSON.parse(lsValue);
            }
        },

        methods: {
            handleResponse (response) {
                Object.assign(this.toastProps, {
                    message: response.message,
                    messageClass: response.success ? 'toast-success' : 'toast-error'
                });
                this.$refs.toast.isActive = true;
            },
            async handleLink (row) {
                let response = await SimpleFetch(this.$options.routes.link + "?article=" + this.instanceId + "&file=" + row.id, 'POST');
                if(response.success) {
                    row.linked = response.status === 'linked';
                }
            },
            storeSort (sort) {
                window.localStorage.setItem(window.location.origin + "/admin/files__sort__", JSON.stringify({ column: sort.sortColumn.prop, dir: sort.sortDir }));
            }
        }
    });
</script>
