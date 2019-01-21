<!-- { extend: admin/layout_with_menu.php @ content_block } -->

<script src="https://cdn.jsdelivr.net/npm/vue"></script>

<script type="text/javascript" src="/js/admin/doUsers.js"></script>

<script type="text/javascript">
	if(!this.vxWeb.parameters) {
		this.vxWeb.parameters = {};
	}
	if(!this.vxWeb.serverConfig) {
		this.vxWeb.serverConfig = {};
	}
	
	
	this.vxWeb.routes.users			= "<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('usersXhr')->getUrl() ?>?<?= vxPHP\Http\Request::createFromGlobals()->getQueryString() ?>";
	this.vxWeb.parameters.usersId	= "<?= vxPHP\Http\Request::createFromGlobals()->query->get('id') ?: '' ?>";

	/*
	vxJS.event.addDomReadyListener(function() {
		vxWeb.doUsers();
	});
	*/
</script>

<h1>User <em class="smaller"><?= $tpl->user ? $tpl->user['name'] : 'neuer User' ?></em></h1>

<div class="vx-button-bar">
    <a class="btn with-webfont-icon-left" data-icon="&#xe025;" href="$users">Zurück zur Übersicht</a>
</div>

<div class="form-content">
    <form action="/admin/users/new" method="POST" class="form-horizontal" v-on:submit.prevent="submit">

        <input name="_csrf_token" value="9Xw0YgzQdpNsPkCtSXbKfCzS-rgkoRkdFHBLazQzmeM" type="hidden">

        <div class="form-sect">

            <div class="form-group">
                <label class="form-label col-3" for="username_input"><strong>Username*</strong></label>
                <div class="col-9">
                    <input name="username" value="" id="username_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.username">
                    <p id="error_username" class="form-input-hint vx-error-box"></p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="email_input"><strong>Email*</strong></label>
                <div class="col-9">
                    <input name="email" value="" id="email_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.email">
                    <p id="error_email" class="form-input-hint vx-error-box"></p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="name_input"><strong>Name*</strong></label>
                <div class="col-9">
                    <input name="name" value="" id="name_input" class="form-input" autocomplete="off" maxlength="128" type="text" v-model="form.name">
                    <p id="error_name" class="form-input-hint vx-error-box"></p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label col-3" for="admingroupsid_select"><strong>Gruppe*</strong></label>
                <select name="admingroupsid" id="admingroupsID_select" class="form-select col-9" v-model="form.admingroupsid">
                    <option v-for="option in options.admingroups" v-bind:value="option.admingroupsid">
                        {{ option.name }}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label col-3" for="pwd_input">Neues Passwort</label>
                <div class="col-9">
                    <input name="new_PWD" value="" id="pwd_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD">
                    <p id="error_new_PWD" class="form-input-hint vx-error-box"></p>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label col-3" for="pwd2_input">Passwort wiederholen</label>
                <div class="col-9">
                    <input name="new_PWD_verify" value="" id="pwd2_input" class="form-input" autocomplete="off" maxlength="128" type="password" v-model="form.new_PWD_verify">
                    <p id="error_new_PWD_verify" class="form-input-hint vx-error-box"></p>
                </div>
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-base">
            <div class="form-group">
                <label class="col-3 form-label"></label><button name="submit_user" value="" type='submit' class='btn btn-success' :class=buttonClass>User anlegen</button>
            </div>
        </div>
    </form>

</div>


<script>
    "use strict";

    document.addEventListener("DOMContentLoaded", function() {

        var postData = function (url = "", data = {}) {

            return fetch(url, {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                headers: {
                    "Content-Type": "application/json"
                },
                referrer: "no-referrer",
                body: JSON.stringify(data)
            })
            .then(response => response.json());

        };

        var app = new Vue({
            el: "#page",
            data: {
                form: {
                    username: "",
                    email: "",
                    name: "",
                    admingroupsid: "",
                    new_PWD: ""
                },
                options: {
                    admingroups: []
                },
                buttonClass: ""
            },
            methods: {
                submit() {
                    this.buttonClass = "loading";

                    postData("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_data_post')->getUrl() ?>")
                        .then(function() { app.buttonClass = ""; });

                }

            }

        });

        fetch("<?= \vxPHP\Application\Application::getInstance()->getRouter()->getRoute('user_data_get')->getUrl() ?>")
                .then(response => response.json())
                .then(function(data) {
                    app.options.admingroups = data.options.admingroups;
                    if(data.formData) {
                        app.form = data.formData;
                    }
                });

    });

</script>