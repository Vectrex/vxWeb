<?php
require_once 'site.config.php';

/**
 * Parameter prüfen/initalisieren
 * passende Seite wählen
 */

use vxPHP\Application\Webpage;

if(!($route = vxPHP\Http\Router::getRouteFromPathInfo())){
	Webpage::generateHttpError();
}

$admin = vxPHP\User\Admin::getInstance();

$controllerClass = $route->getController();
$page = new $controllerClass;

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
