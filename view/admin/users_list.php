<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script src="https://cdn.jsdelivr.net/npm/vue"></script>

<h1>User</h1>

<script type="text/javascript">
    /*
	vxJS.event.addDomReadyListener(function() {
		var lsValue, lsKey = window.location.origin + "/admin/users__sort__",
			t = vxJS.widget.sorTable(
			document.querySelector(".table"),	{
				columnFormat: [
					null,
					null,
					null,
					null,
					"no_sort"
				]
			});

		vxJS.event.addListener(
			t,
			"finishSort",
			function() {
				var c = this.getActiveColumn();
				window.localStorage.setItem(lsKey, JSON.stringify( { ndx: c.ndx, asc: c.asc } ));
			}
		);

		if(window.localStorage) {
            if ((lsValue = window.localStorage.getItem(lsKey))) {
                lsValue = JSON.parse(lsValue);
                t.sortBy(lsValue.ndx, lsValue.asc ? "asc" : "desc");
            }
        }
	});
*/
</script>

<?php $currentUsername = vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername(); ?>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$users/new">User anlegen</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th  v-for="column in columns" class="vx-sortable-header" :class="[ setHeaderClass(column), columnProperties[column].width ? columnProperties[column].width : '' ]" @click="sort(column)">{{ columnProperties[column].label }}</th>
            <th class="col-1"></th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="user in users" :class="{ 'disabled' : currentUser.username === user.username }">

            <td v-for="column in columns" :class="{ 'active': currentSortColumn === column }">{{ user[column] }}</td>
            <td class="right">
                <a v-if="currentUser.username !== user.username" class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'users?id=' + user.username">&#xe002;</a>
                <a v-if="currentUser.username !== user.username" class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" :href="'users/del?id=' + user.username" onclick="return window.confirm('Wirklich löschen?');">&#xe011;</a>
            </td>
        </tr>
    </tbody>

</table>

<script>

    "use strict";

    var app = new Vue({

        el: "table",

        data: {
            users: JSON.parse('<?= json_encode(array_map(function ($u) {
                unset ($u['pwd']);
                return $u;
            }, (array)$this->users)) ?>'),

            currentUser: {
                username: <?= json_encode(vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername()) ?>
            },

            columns: ["username", "name", "email", "alias"],

            columnProperties: {
                username: {label: "Username", sortable: true, width: "col-3" },
                name: { label: "Name", sortable: true, width: "col-2" },
                email: { label: "Email", sortable: false },
                alias: { label: "Gruppe", sortable: true, width: "col-2" }
            },

            currentSortDir: "asc",
            currentSortColumn: null
        },

        computed: {
            fullname: function() { return alias + " " }
        },

        methods: {
            setHeaderClass: function(column) {
                if(this.currentSortColumn === column) {
                    return this.currentSortDir;
                }
                return "";
            },

            sort: function(prop) {

                if(this.columnProperties[prop].sortable) {

                    this.users.sort((a, b) => {

                        if (a[prop] < b[prop]) {
                            return this.currentSortDir === "asc" ? -1 : 1;
                        }
                        if (a[prop] > b[prop]) {
                            return this.currentSortDir === "asc" ? 1 : -1;
                        }

                        return 0;
                    });

                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
                    this.currentSortColumn = prop;

                }

            }
        }

    });
</script>