<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script src="https://cdn.jsdelivr.net/npm/vue"></script>

<h1>User</h1>

<script type="text/javascript">
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

</script>

<?php $currentUsername = vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername(); ?>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$users/new">User anlegen</a>
</div>

<table class="table table-striped">
	<tr>
		<th class="col-3 vx-sortable-header" @click="sort('username')">Username</th>
		<th class="col-2 vx-sortable-header" @click="sort('name')">Name</th>
		<th>Email</th>
		<th class="col-2 vx-sortable-header" @click="sort('alias')">Gruppe</th>
		<th class="col-1"></th>
	</tr>

    <tr v-for="user in users" v-bind:class="{ 'disabled' : currentUser.username === user.username }">

        <td>{{ user.username }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.alias }}</td>
        <td class="right">
                <a v-if="currentUser.username !== user.username" class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" v-bind:href="'users?id=' + user.username">&#xe002;</a>
                <a v-if="currentUser.username !== user.username" class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" v-bind:href="'users/del?id=' + user.username" onclick="return window.confirm('Wirklich löschen?');">&#xe011;</a>
        </td>

    </tr>

</table>

<script>
    "use strict";

    document.addEventListener("DOMContentLoaded", function() {

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
                currentSortDir: "asc"
            },

            methods: {
                sort: function(prop) {

                    this.users.sort((a, b) => {

                        if (a[prop] < b[prop]) {
                            return this.currentSortDir === "asc" ? 1 : -1;
                        }
                        if (a[prop] > b[prop]){
                            return this.currentSortDir === "asc" ? -1 : 1;
                        }

                        return 0;
                    });

                    this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";

                }
            }
        });
    });
</script>