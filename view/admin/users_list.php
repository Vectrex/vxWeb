<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>User</h1>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_add')->getUrl() ?>">User anlegen</a>
    </div>

    <sortable
            :rows="users"
            :columns="cols"
            :sort-prop="initSort.column"
            :sort-direction="initSort.dir"
            ref="sortable"
            @after-sort="storeSort"
            id="users-list"
    >
        <template v-slot:action="slotProps">
            <a v-if="currentUser.username !== slotProps.row.username" class="btn webfont-icon-only tooltip mr-1" data-tooltip="Bearbeiten" :href="'<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_edit')->getUrl()?>?id=' + slotProps.row.key">&#xe002;</a>
            <a v-if="currentUser.username !== slotProps.row.username" class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" href="#" @click.prevent="del(slotProps.row)">&#xe011;</a>
        </template>
    </sortable>
    <confirm ref="confirm" :config="{ cancelLabel: 'Abbrechen', okLabel: 'Löschen', okClass: 'btn-error' }"></confirm>
</div>

<script>
    const { Sortable, Confirm } = window.vxweb.Components;
    const SimpleFetch = window.vxweb.Util.SimpleFetch;

    Vue.createApp({

        components: { "sortable": Sortable, "confirm": Confirm },

        routes: {
            init: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('users_init')->getUrl() ?>",
            delete: "<?= vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_del')->getUrl() ?>"
        },

        data: () => ({
            users: [],
            currentUser: {
            },
            cols: [
                { label: "Username", sortable: true, width: "col-3", prop: "username" },
                { label: "Name", sortable: true, width: "col-2", prop: "name" },
                { label: "Email", prop: "email" },
                { label: "Gruppe", sortable: true, width: "col-2", prop: "alias" },
                { label: "", width: "col-1", prop: "action", cssClass: "text-right" }
            ],
            initSort: {}
        }),

        async created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/users__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }

            let response = await SimpleFetch(this.$options.routes.init);

            this.currentUser = response.currentUser || {};
            this.users = response.users || [];
        },

        methods: {
            async del (row) {
                if(await this.$refs.confirm.open('Benutzer löschen', "Soll der Benutzer wirklich gelöscht werden?")) {
                    let response = await SimpleFetch(this.$options.routes.delete + '?id=' + row.key, 'DELETE');
                    if(response.success) {
                        this.users.splice(this.users.findIndex(item => row === item), 1);
                    }
                }
            },

            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/users__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        }
    }).mount('#app');
</script>