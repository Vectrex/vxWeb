<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<h1>User</h1>

<?php $currentUsername = vxPHP\Application\Application::getInstance()->getCurrentUser()->getUsername(); ?>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-right btn-primary" data-icon="&#xe018;" href="$users/new">User anlegen</a>
</div>

<div id="vue-container">
    <sort-table></sort-table>
</div>

<script>

    "use strict";

    var lsKey = window.location.origin + "/admin/users__sort__", lsValue;

    Vue.component('sort-table', {
        data: function() {
            return {
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

                sort: {
                    column: null,
                    dir: "asc"
                }
            };
        },

        watch: {
            sort: {
                handler: function (val) {
                    this.doSort(val.column, val.dir);
                },
                deep: true
            }
        },

        methods: {
            setHeaderClass: function(column) {

                if(this.sort.column === column) {
                    return this.sort.dir;
                }
                return "";

            },

            clickSort: function(col) {

                if(this.columnProperties[col].sortable) {
                    this.sort = {
                        column: col,
                        dir: this.sort.dir === 'asc' ? 'desc' : 'asc'
                    };
                }

                window.localStorage.setItem(lsKey, JSON.stringify(this.sort));
            },

            doSort: function(prop, dir) {

                this.users.sort((a, b) => {

                    if (a[prop] < b[prop]) {
                        return dir === "asc" ? -1 : 1;
                    }
                    if (a[prop] > b[prop]) {
                        return dir === "asc" ? 1 : -1;
                    }

                    return 0;
                });

            }
        },

        template: `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th
                            v-for="column in columns"
                            :class="[ 'vx-sortable-header', setHeaderClass(column), columnProperties[column].width ? columnProperties[column].width : '' ]"
                            @click="clickSort(column)"
                        >
                            {{ columnProperties[column].label }}
                        </th>
                        <th class="col-1"></th>
                    </tr>
                </thead>
            <tbody>
                <tr v-for="user in users" :class="{ 'disabled' : currentUser.username === user.username }">
                    <td v-for="column in columns" :class="{ 'active': sort.column === column }">{{ user[column] }}</td>
                    <td class="right">
                        <a v-if="currentUser.username !== user.username" class="btn webfont-icon-only tooltip" data-tooltip="Bearbeiten" :href="'users?id=' + user.username">&#xe002;</a>
                        <a v-if="currentUser.username !== user.username" class="btn webfont-icon-only tooltip tooltip-left" data-tooltip="Löschen" :href="'users/del?id=' + user.username" onclick="return window.confirm('Wirklich löschen?');">&#xe011;</a>
                    </td>
                </tr>
            </tbody>
        </table>
    `
    });

    var app = new Vue({ el: "#vue-container" });

    if(window.localStorage && (lsValue = window.localStorage.getItem(lsKey))) {
        // app.sort = JSON.parse(lsValue);
    }

</script>