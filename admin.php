<?php
require_once 'site.config.php';

//$conf = HTMLPurifier_Config::createDefault();
//$conf->set('HTML.Doctype', 'HTML 4.01 Strict');
//$conf->set('HTML.Allowed', 'a[href], p, ul, ol, li, strong, em, sup, sub, br');
//$purifier = new HTMLPurifier($conf);

use vxPHP\User\Admin;
use vxPHP\Request\NiceURI;

$page = $config->initPage();
$page->overrideMetaValue('robots', 'noindex, nofollow');

$admin = Admin::getInstance();

/**
 * HTML starten
 */
	echo $page->htmlHeader(
	'Admin',
	array(
		'admin.css',
		'js.css',
		'admin_site_specific.css'
	),
	array(
		'core.js',
		'widget.js',
		'xhr.js',
		'extra/fx.js',
		'extra/dnd.js',
		'widgets/xhrform.js',
		'widgets/confirm.js',
		'widgets/calendar.js',
		'widgets/sortable.js',
		'widgets/tree.js',
		'widgets/autosuggest.js',
		'widgets/simpletabs.js',
	)
);
?>
	<div id="page">
		<?php if($page->currentPage !== 'login'): ?>
			<div id="statusBar">
				<p>
					<span class="userInfo">Angemeldet <strong><?php echo $admin->getName(); ?></strong> (<?php echo $admin->getId(); ?>)</span> &bull;
					<a class="logoutLink" href="<?php echo NiceURI::autoConvert("admin.php?page=logout"); ?>">Abmelden</a> &bull;
					Gehe zu <a href="/" class="homeLink"><?php echo $_SERVER['HTTP_HOST']; ?></a>
				</p>
			</div>
			<?php echo $page->mainMenu('admin', 0); ?>
			<div id="content">
				<?php echo $page->content(); ?>
			</div>
			<?php else: ?>
				<?php echo $page->content(); ?>
			<?php endif; ?>
	</div>

	<div id="messageBox">&nbsp;</div>

	<?php echo $page->htmlFooter(); ?>
