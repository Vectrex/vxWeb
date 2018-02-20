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
		<link type='text/css' rel='stylesheet' href='/css/admin.min.css'>
        <script type="text/javascript" src="/js/admin/vxjs.js"></script>
    </head>

	<body>

		<div id="page" class="single-column">
            <h1>Konfiguration</h1>
            <div class="divider text-center" data-content="Verzeichnisse"></div>
            <table class="table table-striped">
                <tr>
                    <td>Dateien in <?= $this->default_view_path ?> beschreibbar</td><td><?= $this->checks['default_files_are_writable']  ? '<span class="label label-success">ja</span>' : '<span class="label label-error">nein</span>' ?></td>
                </tr>
                <tr>
                    <td>Ordner <?= $this->default_view_path ?> beschreibbar</td><td><?= $this->checks['default_is_writable']  ? '<span class="label label-success">ja</span>' : '<span class="label label-error">nein</span>' ?></td>
                </tr>
                <tr>
                    <td>Dateien in <?= $this->ini_path ?> beschreibbar</td><td><?= $this->checks['ini_files_are_writable']  ? '<span class="label label-success">ja</span>' : '<span class="label label-error">nein</span>' ?></td>
                </tr>

            </table>
            <div class="divider text-center" data-content="Datenbank Einstellungen"></div>
            <?= $this->db_settings_form ?>
        </div>

    </body>
</html>
