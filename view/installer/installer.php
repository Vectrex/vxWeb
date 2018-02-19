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
            <table class="table">
                <tr>
                    <th>Dateien in <?= $this->default_view_path ?> beschreibbar</th><td><?= $this->default_files_are_writable  ? 'ja' : 'nein' ?></td>
                </tr>
                <tr>
                    <th>Ordner <?= $this->default_view_path ?> beschreibbar</th><td><?= $this->default_is_writable  ? 'ja' : 'nein' ?></td>
                </tr>
                <tr>
                    <th>Dateien in <?= $this->ini_path ?> beschreibbar</th><td><?= $this->ini_files_are_writable  ? 'ja' : 'nein' ?></td>
                </tr>

            </table>
		</div>

    </body>
</html>
