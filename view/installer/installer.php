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
    </head>

	<body>

		<div id="page" class="container grid-md">
            <h1>vxWeb Installer</h1>
            <div class="divider text-center" data-content="Schreibrechte für Verzeichnisse"></div>
            <table class="table table-striped">
                <?php foreach($this->path_checks as $path => $check): ?>
                <tr>
                    <td>Ordner <?= $path ?> beschreibbar</td><td><?= $check['writable']  ? '<span class="label label-success">ja</span>' : '<span class="label label-error">nein</span>' ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <?php if(!$this->paths_ok): ?>

                <div class="toast toast-error">
                    Erlaube zunächst Schreibzugriff auf die derzeit nicht beschreibbaren Ordner und die darin enthaltenen Dateien.<br>
                    Im darauf folgenden Schritt wird dann die Datenbank eingerichtet.
                </div>

            <?php elseif($this->success): ?>

                <div class="toast toast-success my-2">
                    <h2>Installation abgeschlossen</h2>
                    <div>Für den Login wurde als Username <span class="label">admin</span> und das Passwort <span class="label"><?= $this->password ?></span> eingerichtet.<br>
                    Es empfiehlt sich, dies nach dem erstmaligen Login zu ändern.</div>
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

                <div class="divider text-center" data-content="Datenbank Einstellungen"></div>

                <?php if($this->connection_error): ?>
                    <div class="toast toast-error"><?= $this->connection_error ?></div>
                <?php endif; ?>

                <?php if($this->misc_error): ?>
                    <div class="toast toast-error"><?= $this->misc_error ?></div>
                <?php endif; ?>

                <?php if($this->db_settings_form): ?>
                    <?= $this->db_settings_form ?>
                <?php endif; ?>

            <?php endif; ?>

        </div>

    </body>
</html>
