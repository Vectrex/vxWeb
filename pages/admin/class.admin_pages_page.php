<?php

use vxPHP\Util\Rex;

use vxPHP\Template\SimpleTemplate;
use vxPHP\Template\Util\SimpleTemplateUtil;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\ButtonElement;



class admin_pages_page extends page {
	protected $pageRequests = array(
		'id'		=> Rex::INT_EXCL_NULL,
		'action'	=> array('del')
	);

	private $allowNiceEdit = TRUE;

	public function __construct() {
		parent::__construct();

		SimpleTemplateUtil::syncTemplates();

		if(isset($this->validatedRequests['id'])) {
			$rows = $this->db->doQuery("
				select
					p.pagesID,
					p.Alias,
					p.Template,
					r.Keywords,
					r.Title,
					r.Markup,
					r.Description,
					r.Locale,
					date_format(r.templateUpdated, '%Y-%m-%d %H:%i:%s') as lastUpdate
				FROM
					revisions r
					inner join pages p on p.pagesID = r.pagesID
				WHERE
					(p.Locked IS NULL OR p.Locked = 0) AND
					r.revisionsID = {$this->validatedRequests['id']}
				", TRUE);

			if(empty($rows)) {
				$this->redirect('admin.php?page=pages');
			}

			$page = $rows[0];
			$this->allowNiceEdit = !preg_match('~<\\?(php)?.*?\\?>~', $page['Markup']);
			$this->editForm = new HtmlForm('admin_page_edit.htm');
			$this->editForm->addElement(FormElementFactory::create('input', 'Title', $page['Title'], array('maxlength' => 128, 'class' => 'pct_100'), array(), FALSE, array('trim')));
			$this->editForm->addElement(FormElementFactory::create('input', 'Alias', $page['Alias'], array('maxlength' => 64, 'class' => 'pct_100'), array(), TRUE, array('trim', 'uppercase')));
			$this->editForm->addElement(FormElementFactory::create('textarea', 'Keywords', $page['Keywords'], array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')));
			$this->editForm->addElement(FormElementFactory::create('textarea', 'Description', $page['Description'], array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')));
			$this->editForm->addElement(FormElementFactory::create('textarea', 'Markup', htmlspecialchars($page['Markup'], ENT_NOQUOTES, 'UTF-8'), array('rows' => 20, 'cols' => 40, 'class' => 'pct_100'), array(), FALSE, array('trim')));
			$submit = new ButtonElement('submit_edit', '', 'submit');
			$submit->setInnerHTML('<span>Änderungen übernehmen</span>');
			$this->editForm->addElement($submit);

			$this->editForm->initVar('success', isset($this->validatedRequests['success']) ? 1 : 0);
			$this->editForm->initVar('nochange', isset($this->validatedRequests['nochange']) ? 1 : 0);

			if($this->editForm->wasSubmittedByName('submit_edit')) {
				$v = $this->editForm->getValidFormValues();

				if(
					$v['Title']			== $page['Title'] &&
					$v['Description']	== $page['Description'] &&
					$v['Keywords']		== $page['Keywords'] &&
					$v['Markup']		== htmlspecialchars($page['Markup'])
				) {
					$this->redirect("admin.php?page=pages&id={$this->validatedRequests['id']}&nochange");
				}

				if(($newId = SimpleTemplateUtil::addRevision(array(
					'authorsID' => $_SESSION['user']['ID'],

					'Title' => $v['Title'],
					'Markup' => $v['Markup'],
					'Keywords' => $v['Keywords'],
					'Description' => $v['Description'],
					'templateUpdated' => date('Y-m-d H:i:s'),

					'pagesID' => $page['pagesID'],
					'Locale' => $page['Locale']
				)))) {
					$this->redirect("admin.php?page=pages&id=$newId&success");
				}
				$this->editForm->setError('system');
			}
			return;
		}

		$this->data['pages'] = $this->db->doQuery("
			select
				pg.*,
				rev.revisionsID,
				rev.Rawtext,
				IFNULL(rev.Title, pg.PageTitle) as `Title`
			from
				(SELECT
					p.pagesID,
					p.Template,
					p.Alias,
					p.Title as PageTitle,
					IF(r.Locale IS NULL OR r.Locale = '', 'universal', r.Locale) as Locale,
					MAX(r.templateUpdated) as LastRevision,
					COUNT(r.revisionsID) as RevCount
				FROM
					pages p
					inner join revisions r on r.pagesID = p.pagesID
				WHERE
					(p.Locked IS NULL OR p.Locked = 0)
				GROUP BY
					pagesID, Alias, Locale)
				as pg
				inner join revisions rev ON (rev.templateUpdated = pg.LastRevision AND rev.pagesID = pg.pagesID)
			ORDER BY
				Alias,
				Locale", TRUE, 'htmlspecialchars');
	}

	public function content() {
		if(isset($this->editForm)) {
			$tpl = new SimpleTemplate('admin_page_edit.htm');
			$tpl->assign('form', $this->editForm->render());
			$tpl->assign('allow_nice_edit', $this->allowNiceEdit);
		}
		else {
			$tpl = new SimpleTemplate('admin_pages_list.htm');
			$tpl->assign('pages', $this->data['pages']);
			$tpl->addFilter('shortenText');
		}
		$html = $tpl->display();
		$this->html .= $html;
		return $html;
	}
}
?>