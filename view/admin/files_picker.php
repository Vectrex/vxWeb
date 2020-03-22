<!-- { extend: admin/layout_dialog.php @ content_block } -->

<div id="app" v-cloak>
    <filemanager :routes="$options.routes" :columns="cols" :init-sort="initSort" ref="fm" @response-received="handleResponse" @after-sort="storeSort">
        <template v-slot:action="slotProps">
            <button v-if="slotProps.row.isFolder" class="btn webfont-icon-only tooltip delFolder" data-tooltip="Ordner leeren und löschen" @click="$refs.fm.delFolder(slotProps.row)">&#xe008;</button>
            <template v-else>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" type="button" @click="$refs.fm.editFile(slotProps.row)">&#xe002;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Verschieben" type="button" @click="$refs.fm.getFolderTree(slotProps.row)">&#xe004;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Löschen" type="button" @click="$refs.fm.delFile(slotProps.row)">&#xe011;</button>
                <button class="btn webfont-icon-only tooltip" data-tooltip="Übernehmen" type="button" @click="forward(slotProps.row)">&#xe01e;</button>
            </template>
        </template>
    </filemanager>

    <message-toast
        :message="toastProps.message"
        :classname="toastProps.messageClass"
        :active="toastProps.isActive"
        ref="toast"
    />
</div>

<?php $router = \vxPHP\Application\Application::getInstance()->getRouter(); ?>
<?php $filter = \vxPHP\Http\Request::createFromGlobals()->query->get('filter'); ?>
<?php $ckFuncNum = \vxPHP\Http\Request::createFromGlobals()->query->get('CKEditorFuncNum'); ?>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>

    const MessageToast = window.vxweb.Components.MessageToast;
    const Filemanager = window.vxweb.Components.Filemanager;

    let app = new Vue({

        el: "#app",
        components: { 'message-toast': MessageToast, 'filemanager': Filemanager },

        routes: {
            init: "<?= $router->getRoute('files_init')->getUrl() ?>?filter=<?= $filter ?>",
            readFolder: "<?= $router->getRoute('folder_read')->getUrl() ?>?filter=<?= $filter ?>",
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

        data: {
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
                { label: "Größe", sortable: true, prop: "size" },
                { label: "Typ/Vorschau", sortable: true, prop: "type" },
                { label: "Erstellt", sortable: true, prop: "modified"},
                { label: "", prop: "action" }
            ],
            initSort: {},
            toastProps: {
                message: "",
                messageClass: "",
                isActive: false
            }
        },

        created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/files__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
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
            storeSort (sort) {
                window.localStorage.setItem(window.location.origin + "/admin/files__sort__", JSON.stringify({ column: sort.sortColumn.prop, dir: sort.sortDir }));
            },
            forward (row) {
                window.opener.CKEDITOR.tools.callFunction(<?= $ckFuncNum ?>, row.path);
                window.close();
            }
        }
    });
</script>