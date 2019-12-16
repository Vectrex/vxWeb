<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<div id="app">

    <h1>User</h1>

    <?php $currentUsername = vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername(); ?>

    <div class="vx-button-bar">
        <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$users/new">User anlegen</a>
    </div>

    <sortable
            :rows="users"
            :columns="cols"
            :sort-prop="initSort.column"
            :sort-direction="initSort.dir"
            ref="sortable"
            @after-sort="storeSort"
    >
        <template v-slot:action="slotProps">
            <a v-if="currentUser.username !== slotProps.row.username" class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'users?id=' + slotProps.row.username">&#xe002;</a>
            <a v-if="currentUser.username !== slotProps.row.username" class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" :href="'users/del?id=' + slotProps.row.username" onclick="return window.confirm('Wirklich löschen?');">&#xe011;</a>
        </template>
    </sortable>
</div>

<script type="module">

    import Sortable from  "/js/vue/components/sortable.js";

    let app = new Vue({

        el: "#app",
        components: { "sortable": Sortable },

        data: {
            users: JSON.parse('<?= json_encode(array_map(function ($u) {
                unset ($u['pwd']);
                return $u;
            }, (array)$this->users)) ?>'),
            currentUser: {
                username: <?= json_encode(vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername()) ?>
            },
            cols: [
                { label: "Username", sortable: true, width: "col-3", prop: "username" },
                { label: "Name", sortable: true, width: "col-2", prop: "name" },
                { label: "Email", prop: "email" },
                { label: "Gruppe", sortable: true, width: "col-2", prop: "alias" },
                { label: "", width: "col-1", prop: "action", cssClass: "text-right" }
            ],
            initSort: {}
        },

        created () {
            let lsValue = window.localStorage.getItem(window.location.origin + "/admin/users__sort__");
            if(lsValue) {
                this.initSort = JSON.parse(lsValue);
            }
        },

        methods: {
            storeSort () {
                window.localStorage.setItem(window.location.origin + "/admin/users__sort__", JSON.stringify({ column: this.$refs.sortable.sortColumn.prop, dir: this.$refs.sortable.sortDir }));
            }
        }
    });
</script>