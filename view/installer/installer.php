<!doctype html>

<html>
	<head>
		<title>vxWeb Installer</title>
		<meta name='keywords' content=''>
		<meta name='description' content=''>
		<meta name='robots' content='noindex, nofollow'>

		<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
		<meta name='author' content='Gregor Kofler - Mediendesign und Webapplikationen, http://gregorkofler.com'>

		<link rel='icon' type='image/x-icon' href='/favicon.ico'>

		<link type='text/css' rel='stylesheet' href='<?= \vxPHP\Application\Application::getInstance()->asset('style.css') ?>'>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>

	<body class="min-h-screen w-full lg:w-1/2 lg:mx-auto px-1 py-4">
		<div>
            <h1 class="text-3xl text-vxvue">vxWeb Installer</h1>

            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-white px-2 text-sm text-gray-500">Schreibrechte für Verzeichnisse</span>
                </div>
            </div>

            <?php foreach($this->path_checks as $path => $check): ?>
            <div class="flex justify-between items-center py-2">
                <span>Ordner <pre class="inline-block text-vxvue-alt-600 font-bold"><?= $path ?></pre> beschreibbar</span>
                <span class="w-20 font-bold text-white text-center p-2 rounded <?= $check['writable']  ? 'bg-green-600' : 'bg-red-600' ?>">
                    <?= $check['writable']  ? 'ja' : 'nein' ?>
                </span>
            </div>
            <?php endforeach; ?>

            <?php if(!$this->paths_ok): ?>

                <div class="border-l-4 border-red-600 bg-red-50 p-4 rounded">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-red-800">
                                Erlaube zunächst Schreibzugriff auf die derzeit nicht beschreibbaren Ordner und die darin enthaltenen Dateien.<br>
                                Im darauf folgenden Schritt wird dann die Datenbank eingerichtet.
                            </p>
                        </div>
                    </div>
                </div>

                <?php if($this->installer_is_deletable): ?>
                <p class="my-2">
                    <a class="btn btn-success" href="installer.php?delete">Lösche Installer und gehe zum Admin Login</a>
                    <a class="btn" href="<?= $this->admin_url?>" target="_blank">Gehe zum Admin Login ohne den Installer zu löschen</a>
                </p>

                <?php else: ?>

                <div class="toast toast-error my-2">
                       Das Installer Skript kann nicht gelöscht werden. Entferne die Datei<br><strong><?= $this->installer_file ?></strong><br>manuell.
                </div>
                <p class="my-2">
                    <a class="btn" href="<?= $this->admin_url?>" target="_blank">Gehe zum Admin Login</a>
                </p>

                <?php endif; ?>


            <?php else: ?>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-2 text-sm text-gray-500">Datenbank Einstellungen</span>
                    </div>
                </div>

                <?php if($this->connection_error): ?>
                    <div class="toast toast-error"><?= $this->connection_error ?></div>
                <?php endif; ?>

                <?php if($this->misc_error): ?>
                    <div class="toast toast-error"><?= $this->misc_error ?></div>
                <?php endif; ?>

                <div class="py-2 space-y-2" x-data="dbform">
                    <template x-for="field in fields" :key="field.name">
                        <div class="rounded px-3 pb-1.5 pt-2.5 shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-vxvue-500">
                            <label
                                :for="'form-' + field.name"
                                :class="'block text-xs font-medium ' + (errors[field.name] ? 'text-red-500' : 'text-gray-900')"
                                x-text="field.label">
                            </label>

                            <template x-if="!field.type">
                                <input
                                    type="text"
                                    :id="'form-' + field.name"
                                    class="block w-full border-0 p-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    :placeholder="field.placeholder || ''"
                                    x-model="form[field.name]"
                                >
                            </template>

                            <template x-if="field.type === 'select'">
                                <select
                                    :id="'form-' + field.name"
                                    class="block w-full border-0 p-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                    x-model="form[field.name]"
                                >
                                    <template x-if="field.placeholder">
                                        <option value="" disabled selected x-text="field.placeholder"></option>
                                    </template>
                                    <template x-for="option in field.options">
                                        <option :value="option.value" x-text="option.label"></option>
                                    </template>
                                </select>
                            </template>
                        </div>
                    </template>

                    <div class="py-2">
                        <button class="button w-full" type="button" @click.prevent="submit">Übernehmen</button>
                    </div>

                    <div class="bg-green-600 text-white font-medium px-8 py-4 rounded space-y-2" x-show="response.success">
                        <h2 class="text-2xl">Installation abgeschlossen</h2>
                        <div>Für den Login wurde als Username <pre class="inline-block bg-green-900 px-2 py-1 rounded-sm">admin</pre> und das Passwort <pre class="inline-block bg-green-900 px-2 py-1 rounded-sm" x-text=""></pre> eingerichtet.<br>
                            Es empfiehlt sich, dies nach dem erstmaligen Login zu ändern.</div>
                    </div>
                </div>

            <?php endif; ?>

        </div>
        <script>
            document.addEventListener('alpine:init', () => {
              Alpine.data ('dbform', () => ({
                  form: {},
                  errors: {},
                  busy: false,
                  response: {},
                  fields: [
                      { name: 'host', label: 'Host', required: true, placeholder: 'localhost' },
                      { name: 'port', label: 'Port', placeholder: '3306' },
                      { name: 'dbname', label: 'Name der Datenbank', required: true },
                      { name: 'user', label: 'Datenbankuser', required: true },
                      { name: 'password', label: 'Passwort des Datenbankusers', required: true },
                      {
                          name: 'db_type',
                          type: 'select',
                          label: 'Typ',
                          required: true,
                          placeholder: 'MySQL oder PostgreSQL wählen...',
                          options: [
                              { value: 'mysql', label: 'MySQL/MariaDB' },
                              { value: 'pgsql', label: 'PostgreSQL' },
                          ]
                      }
                  ],
                  init () {
                      this.initForm();
                  },
                  initForm () {
                      this.fields.forEach(item => this.form[item.name] = null);
                      this.errors = {};
                  },
                  async submit () {
                      if (this.busy) {
                          return;
                      }
                      this.busy = true;
                      this.response = {};

                      let data = {}, errors = {};

                      this.fields.forEach(field => {
                          let fdata = this.form[field.name];
                          if(field.required && (!fdata || (typeof fdata === 'string' && !fdata.trim()))) {
                              errors[field.name] = true;
                          }
                          else {
                              switch (typeof fdata) {
                                  case 'undefined':
                                      data[field.name] ='';
                                      break;
                                  case 'string':
                                      data[field.name] = fdata.trim();
                                      break;
                              }
                          }
                      });

                      this.errors = errors;

                      if (!Object.keys(errors).length) {
                          try {
                              const responseJson = await fetch('installer.php', {
                                  method: 'POST',
                                  body: JSON.stringify(data)
                              });
                              this.response = await responseJson.json();
                              if (this.response.success) {
                                  this.initForm();
                              }
                              else {
                                  (this.response.errors || []).forEach(err => this.errors[err] = true);
                              }
                          }
                          catch (e) {}
                      }
                      this.busy = false;
                  }
              }))
            })
        </script>
    </body>
</html>
