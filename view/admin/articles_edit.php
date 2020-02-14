<!-- { extend: admin/layout_with_menu.php @ content_block } -->
<?php $router = \vxPHP\Application\Application::getInstance()->getRouter(); ?>

<h1>Artikel &amp; News <em class="text-smaller"><?= $tpl->title ?></em></h1>

<div id="vue-root" v-cloak>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('articles')->getUrl() ?>">Zurück zur Übersicht</a>
    </div>


    <message-toast
            :message="toastProps.message"
            :classname="toastProps.messageClass"
            :active="toastProps.isActive"
            ref="toast"
    ></message-toast>

    <tab :items="tabItems" :active-index.sync="activeTabIndex"></tab>

    <section id="article-form" v-if="activeTabIndex === 0">
        <h1>Form</h1>
    </section>
    <section id="article-files" v-if="activeTabIndex === 1">
        <h1>Files</h1>
        <filemanager :routes="fmProps.routes" :columns="fmProps.cols" :init-sort="fmProps.initSort" ref="fm" @response-received="handleResponse" @after-sort="storeSort">
            <template v-slot:action="slotProps">
                <button v-if="slotProps.row.isFolder" class="btn webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="$refs.fm.delFolder(slotProps.row)">&#xe008;</button>
                <template v-else>
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFile(slotProps.row)">&#xe002;</button>
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="$refs.fm.getFolderTree(slotProps.row)">&#xe004;</button>
                    <button class="btn webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="$refs.fm.delFile(slotProps.row)">&#xe011;</button>
                </template>
            </template>
            <template v-slot:linked="slotProps">
                <label class="form-checkbox" v-if="!slotProps.row.isFolder"><input type="checkbox" @click="handleLink(slotProps.row)"><i class="form-icon"></i></label>
            </template>
        </filemanager>
    </section>
    <section id="article-files-sort" v-if="activeTabIndex === 2">
        <h1>Sort</h1>
    </section>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>
    const components = window.vxweb.Components;
    const MessageToast = components.MessageToast;
    const Tab = components.Tab;
    const Filemanager = components.Filemanager;
    const SimpleFetch =  components.SimpleFetch;

    Vue.component('z-link', components.ZLink);

    const app = new Vue({

        el: '#vue-root',

        components: {
            "message-toast": MessageToast,
            "tab": Tab,
            "filemanager": Filemanager
        },

        data: {
            activeTabIndex: 0,
            tabItems: [
                {name: 'Inhalt', selector: "#article-form"},
                {name: 'Dateien', selector: "#article-files"},
                {name: 'Sortierung', selector: "#article-files-sort"}
            ],
            toastProps: {},
            fmProps: {
                routes: {
                    init: "<?= $router->getRoute('files_init')->getUrl() ?>",
                    readFolder: "<?= $router->getRoute('folder_read')->getUrl() ?>",
                    getFile: "<?= $router->getRoute('file_get')->getUrl() ?>",
                    updateFile: "<?= $router->getRoute('file_update')->getUrl() ?>",
                    uploadFile: "<?= $router->getRoute('file_upload')->getUrl() ?>",
                    delFile: "<?= $router->getRoute('file_del')->getUrl() ?>",
                    renameFile: "<?= $router->getRoute('file_rename')->getUrl() ?>",
                    moveFile: "<?= $router->getRoute('file_move')->getUrl() ?>",
                    getFoldersTree: "<?= $router->getRoute('folders_tree')->getUrl() ?>",
                    delFolder: "<?= $router->getRoute('folder_del')->getUrl() ?>",
                    renameFolder: "<?= $router->getRoute('folder_rename')->getUrl() ?>",
                    addFolder: "<?= $router->getRoute('folder_add')->getUrl() ?>"
                },
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
                    {label: "", sortable: true, prop: "linked"},
                    {label: "Größe", sortable: true, prop: "size"},
                    {label: "Typ/Vorschau", sortable: true, prop: "type"},
                    {label: "Erstellt", sortable: true, prop: "modified"},
                    {label: "", prop: "action"}
                ],
                initSort: {}
            }
        },

        created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/files__sort__");
            if(lsValue) {
                this.fmProps.initSort = JSON.parse(lsValue);
            }
        },

        methods: {
            handleResponse (response) {
                this.toastProps = {
                    message: response.message,
                    messageClass: response.success ? 'toast-success' : 'toast-error',
                    isActive: true
                };
            },
            storeSort (sort) {
                window.localStorage.setItem(window.location.origin + "/admin/files__sort__", JSON.stringify({ column: sort.sortColumn.prop, dir: sort.sortDir }));
            },
            handleLink (row) {
                console.log(row);
            }
        }
    });
</script>
