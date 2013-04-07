<?php
require_once 'site.config.php';

/**
 * Parameter prüfen/initalisieren
 * passende Seite wählen
 */
$page = $config->initPage();

$admin = Admin::getInstance();

/**
 * HTML starten
 */
	echo $page->htmlHeader(' vxPHP File Browser', array(
		'admin.css',
		'js.css'
	),
	array(
		'core.js',
		'widget.js',
		'xhr.js',
		'extra/dnd.js',
		'widgets/xhrform.js',
		'widgets/confirm.js',
		'widgets/sortable.js',
		'widgets/tree.js'
	));
?>
	<div id="page" class="embedded" style="padding: 1em;">
		<?php echo $page->content(); ?>
	</div>
<?php echo $page->htmlFooter(); ?>
