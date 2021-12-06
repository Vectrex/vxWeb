<!-- {extend: admin/layout_with_menu.php @ content_block } -->
<h1>Dateien</h1>

<div id="app" v-cloak>
    <filemanager :limits="$options.limits" :routes="$options.routes" :columns="cols" :init-sort="initSort" ref="fm" @response-received="handleResponse" @after-sort="storeSort">
        <template v-slot:action="slotProps">
            <template v-if="slotProps.row.isFolder">
                <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFolder(slotProps.row)">&#xe002;</button>
                <button class="btn btn-link webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="$refs.fm.delFolder(slotProps.row)">&#xe008;</button>
            </template>
            <template v-else>
                <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFile(slotProps.row)">&#xe002;</button>
                <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="$refs.fm.moveFile(slotProps.row)">&#xe02d;&#xe032;&#xe00e;</button>
                <button class="btn btn-link webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="$refs.fm.delFile(slotProps.row)">&#xe011;</button>
            </template>
        </template>
    </filemanager>
    <message-toast v-bind="toastProps" ref="toast" @cancel="toastProps.active = false" @timeout="toastProps.active = false"></message-toast>
</div>
<?php $router = \vxPHP\Application\Application::getInstance()->getRouter() ?>

<script>
    const MessageToast = window.vxweb.Components.MessageToast;
    const Filemanager = window.vxweb.Components.Filemanager;

    Vue.createApp({

        el: "#app",
        components: { 'message-toast': MessageToast, 'filemanager': Filemanager },

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
            getFolder: "<?= $router->getRoute('folder_get')->getUrl() ?>",
            updateFolder: "<?= $router->getRoute('folder_update')->getUrl() ?>",
            addFolder: "<?= $router->getRoute('folder_add')->getUrl() ?>",
            search:  "<?= $router->getRoute('files_search')->getUrl() ?>",
            delSelection: "<?= $router->getRoute('selection_del')->getUrl() ?>",
            moveSelection: "<?= $router->getRoute('selection_move')->getUrl() ?>"
        },
        limits: {
            maxExecutionTime: <?= $this->max_execution_time_ms ?>,
            maxUploadFilesize:  <?= $this->upload_max_filesize ?>
        },

        data: () => ({
            cols: [
                {
                    label: "",
                    prop: "checked"
                },
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
                { label: "Größe", sortable: true, prop: "size" },
                { label: "Typ/Vorschau", sortable: true, prop: "type" },
                { label: "Erstellt", sortable: true, prop: "modified"},
                { label: "", prop: "action" }
            ],
            initSort: {},
            toastProps: {
                message: "",
                classname: "",
                active: false
            }
        }),

        created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/files__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }
        },

        methods: {
            handleResponse (response) {
                this.toastProps = {
                    message: response.message,
                    classname: response.success ? 'toast-success' : 'toast-error',
                    active: true
                };
            },
            storeSort (sort) {
                window.localStorage.setItem(window.location.origin + "/admin/files__sort__", JSON.stringify({ column: sort.sortColumn.prop, dir: sort.sortDir }));
            }
        }
    }).mount('#app');
</script>