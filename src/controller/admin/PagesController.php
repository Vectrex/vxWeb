<?php
use vxPHP\Template\SimpleTemplate;

use vxPHP\Form\HtmlForm;
use vxPHP\Form\FormElement\FormElementFactory;
use vxPHP\Form\FormElement\ButtonElement;
use vxPHP\Template\Filter\ShortenText;
use vxPHP\Controller\Controller;
use vxPHP\Http\Response;
use vxPHP\Application\Application;
use vxPHP\User\Admin;
use vxPHP\User\User;

class PagesController extends Controller {

	private $allowNiceEdit = TRUE;

	protected function execute() {

		vxWeb\SimpleTemplateUtil::syncTemplates();

		if(($id = $this->request->query->getInt('id'))) {

			$rows = Application::getInstance()->getDb()->doPreparedQuery("
				SELECT
					p.pagesID,
					p.Alias,
					p.Template,
					r.Keywords,
					r.Title,
					r.Markup,
					r.Description,
					r.Locale,
					DATE_FORMAT(r.templateUpdated, '%Y-%m-%d %H:%i:%s') as lastUpdate
				FROM
					revisions r
					INNER JOIN pages p ON p.pagesID = r.pagesID
				WHERE
					(p.Locked IS NULL OR p.Locked = 0) AND
					r.revisionsID = ?
				", array(
					(int) $id
				)
			);

			if(empty($rows)) {
				$this->redirect('pages');
			}

			$page = $rows[0];

			$this->allowNiceEdit = !preg_match('~<\\?(php)?.*?\\?>~', $page['Markup']);

			$submit = new ButtonElement('submit_edit', '', 'submit');
			$submit->setInnerHTML('<span>Änderungen übernehmen</span>');

			$form = HtmlForm::create('admin_page_edit.htm')
				->initVar('success', (int) !is_null($this->request->query->get('success')))
				->initVar('nochange', (int) !is_null($this->request->query->get('nochange')))
				->addElement(FormElementFactory::create('input', 'Title', $page['Title'], array('maxlength' => 128, 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('input', 'Alias', $page['Alias'], array('maxlength' => 64, 'class' => 'pct_100'), array(), TRUE, array('trim', 'uppercase')))
				->addElement(FormElementFactory::create('textarea', 'Keywords', $page['Keywords'], array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('textarea', 'Description', $page['Description'], array('rows' => 4, 'cols' => '30', 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement(FormElementFactory::create('textarea', 'Markup', htmlspecialchars($page['Markup'], ENT_NOQUOTES, 'UTF-8'), array('rows' => 20, 'cols' => 40, 'class' => 'pct_100'), array(), FALSE, array('trim')))
				->addElement($submit);

			if($form->bindRequestParameters()->wasSubmittedByName('submit_edit')) {
				$v = $form->getValidFormValues();

				if(
					$v['Title']			== $page['Title'] &&
					$v['Description']	== $page['Description'] &&
					$v['Keywords']		== $page['Keywords'] &&
					$v['Markup']		== $page['Markup']
				) {
					$this->redirect('pages', NULL, array('id' => $id, 'nochange' => 'true'));
				}

				if(($newId = vxWeb\SimpleTemplateUtil::addRevision(array(
					'authorsID' => User::getSessionUser()->getAdminId(),

					'Title' => $v['Title'],
					'Markup' => $v['Markup'],
					'Keywords' => $v['Keywords'],
					'Description' => $v['Description'],
					'templateUpdated' => date('Y-m-d H:i:s'),

					'pagesID' => $page['pagesID'],
					'Locale' => $page['Locale']
				)))) {
					$this->redirect('pages', NULL, array('id' => $newId, 'success' => 'true'));
				}
				$form->setError('system');
			}

			return new Response(
				SimpleTemplate::create('admin/page_edit.php')
					->assign('form', $form->render())
					->assign('allow_nice_edit', $this->allowNiceEdit)
					->display()
			);
		}

		$pages = Application::getInstance()->getDb()->doPreparedQuery("
			SELECT
				pg.*,
				rev.revisionsID,
				rev.Rawtext,
				IFNULL(rev.Title, pg.PageTitle) AS `Title`
			FROM
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
				Locale
		");

		return new Response(
			SimpleTemplate::create('admin/pages_list.php')
				->assign('pages', $pages)
				->addFilter(new ShortenText())
				->display()
		);
	}
}
