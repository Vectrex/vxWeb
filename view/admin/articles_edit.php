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

    <tab :items="tabItems" :active-index="activeTabIndex" v-on:update:active-index="switchTabs"></tab>

    <section id="article-form" v-if="activeTabIndex === 0" class="form-content">
        <article-form :url="formProps.url" :options="formProps.options" :initial-data="formProps.form" :editor-config="editorConfig" @response-received="handleResponse"></article-form>
    </section>

    <section id="article-files" v-if="activeTabIndex === 1">
        <filemanager
            :routes="fmRoutes"
            :columns="fmProps.cols"
            :init-sort="fmProps.initSort"
            :folder="fmProps.folder"
            :limits="$options.limits"
            ref="fm"
            @response-received="handleResponse"
            @after-sort="storeSort"
        >
            <template v-slot:action="slotProps">
                <button v-if="slotProps.row.isFolder" class="btn btn-link webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="$refs.fm.delFolder(slotProps.row)">&#xe008;</button>
                <template v-else>
                    <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFile(slotProps.row)">&#xe002;</button>
                    <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="$refs.fm.getFolderTree(slotProps.row)">&#xe004;</button>
                    <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="$refs.fm.delFile(slotProps.row)">&#xe011;</button>
                </template>
            </template>
            <template v-slot:linked="slotProps">
                <label class="form-checkbox" v-if="!slotProps.row.isFolder"><input type="checkbox" @click="handleLink(slotProps.row)" :checked="slotProps.row.linked"><i class="form-icon"></i></label>
            </template>
            <template v-slot:linked-header="slotProps"><span class="webfont-icon-only">&#xe013;</span></template>
        </filemanager>
    </section>

    <section id="article-files-sort" v-if="activeTabIndex === 2">
        <slicksort-list v-model="linkedFiles" lock-axis="y" helper-class="slick-sort-helper" @input="saveSort" :use-drag-handle="true">
            <template v-slot:row="slotProps">
                <div class="d-inline-block col-2">{{ slotProps.item.filename }}</div>
                <div class="d-inline-block col-2">
                    <img :src="slotProps.item.type" alt="" v-if="slotProps.item.isThumb" class="img-responsive">
                    <div style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" v-else>{{ slotProps.item.type }}</div>
                </div>
                <div class="d-inline-block col-2">
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Verlinkung entfernen" type="button" @click="unlinkSort(slotProps.item)">&#xe014;</button>
                    <button class="btn webfont-icon-only tooltip" :data-tooltip="slotProps.item.hidden ? 'Anzeigen' : 'Verstecken'" type="button" @click="toggleVisibility(slotProps.item)">{{ slotProps.item.hidden ? '&#xe015;' : '&#xe016;' }}</button>
                </div>
                <a class="d-inline-block col-3" :href="'#' + slotProps.item.folderid" @click.prevent="gotoFolder(slotProps.item.folderid)">{{ slotProps.item.path }}</a>
            </template>
        </slicksort-list>
    </section>
</div>

<script>
    const { MessageToast, Tab, Filemanager, ArticleForm, SlicksortList } = window.vxweb.Components;
    const SimpleFetch = window.vxweb.Util.SimpleFetch;

    Vue.directive('handle', window.vxweb.Directives.HandleDirective);
    Vue.component('z-link', window.vxweb.Components.ZLink);

    const app = new Vue({

        el: '#vue-root',

        components: {
            "message-toast": MessageToast,
            "tab": Tab,
            "filemanager": Filemanager,
            "article-form": ArticleForm,
            "slicksort-list": SlicksortList
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
            linkedFiles: [],
            activeTabIndex: 0,
            tabItems: [
                { name: 'Inhalt', disabled: true },
                { name: 'Dateien', disabled: true },
                { name: 'Sortierung', disabled: true }
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
                initSort: {},
                folder: null
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
            link: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('article_link_file')->getUrl() ?>",
            getLinkedFiles: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('get_linked_files')->getUrl() ?>",
            updateLinkedFiles: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('update_linked_files')->getUrl() ?>",
            toggleLinkedFile: "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('toggle_linked_files')->getUrl() ?>"
        },

        limits: {
            maxExecutionTime: <?= $this->max_execution_time_ms ?>,
            maxUploadFilesize:  <?= $this->upload_max_filesize ?>
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
                this.instanceId = response.articleId || this.instanceId;
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
            },
            async switchTabs (payload) {
                this.activeTabIndex = payload;
                if(payload === 2) {
                    let id = this.instanceId;
                    if (id) {
                        let response = await SimpleFetch(this.$options.routes.getLinkedFiles + '?article=' + id);
                        this.linkedFiles = response.files || [];
                    }
                }
            },
            async saveSort () {
                let ids = [];
                this.linkedFiles.forEach(f => ids.push(f.id));
                let response = await SimpleFetch(this.$options.routes.updateLinkedFiles + '?article=' + this.instanceId, 'POST', {}, JSON.stringify({ fileIds: ids }));
            },
            unlinkSort (file) {
                this.linkedFiles.splice(this.linkedFiles.indexOf(file), 1);
                this.saveSort();
            },
            async toggleVisibility (file) {
                let response = await SimpleFetch(this.$options.routes.toggleLinkedFile + '?article=' + this.instanceId, 'POST', {}, JSON.stringify({ fileId: file.id }));
                if (response.success) {
                    file.hidden = !!response.hidden;
                }
            },
            gotoFolder (folderId) {
                this.fmProps.folder = folderId;
                this.switchTabs(1);
            }
        }
    });
</script>
