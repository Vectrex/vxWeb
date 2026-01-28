<?php
    /*
     * This file is part of the vxPHP/vxWeb framework
     *
     * (c) Gregor Kofler <info@gregorkofler.com>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

    namespace vxWeb\Util;

    use vxPHP\Application\Application;
    use vxPHP\Application\Locale\Locale;
    use vxPHP\File\Exception\FilesystemFolderException;
    use vxPHP\File\FilesystemFolder;
    use vxWeb\Model\Page\Page;
    use vxWeb\Model\Page\Revision;

    /**
     * helper class to sync and update templates both in filesystem and database
     *
     * @author Gregor Kofler, info@gregorkofler.com
     * @version 0.7.0 2026-01-28
     *
     */
    class Template
    {
        /**
         * public function for
         * syncing file and db-based templates
         *
         * if file exists, but no db entry -> import and set active
         * else if db entry exists, but no file -> export
         * else if db entry and file exist ->
         *        if filemtime is older than active db entry -> export
         *        else if filemtime is newer than the newest revision in db (not necessarily active) -> import and set active
         *        else if filemtime between active revision and the newest revision -> export active revision
         *
         * manually edited and saved templates are only prioritised if they are newer than any db entry
         */
        public static function syncTemplates(): void
        {
            $app = Application::getInstance();
            $locales = $app->getAvailableLocales();

            // fetch file templates

            // universal templates

            $fileTpl = [];
            self::getTplFiles($fileTpl);

            // localized templates

            foreach ($locales as $locale) {
                self::getTplFiles($fileTpl, $locale);
            }

            // update templates based on db info

            $pages = Page::getInstances() ?? [];

            $pagesToExport = [];

            foreach ($pages as $page) {

                /* @var Page $page */

                $filename = $page->getTemplate();
                $activeRevision = $page->getActiveRevision();
                $newestRevision = $page->getNewestRevision();

                // file template not found

                if (!isset($fileTpl['universal'][$filename])) {

                    // store revision id to create a file template

                    $pagesToExport[] = $page;

                } else {

                    /*
                     * compare timestamps
                     *
                     * do nothing, when size then hash match
                     *
                     * create the initial revision when no revision exists; create a page with an initial revision when the page does not exist
                     * create a new revision when the active or newest revision in db is older than the file template
                     * create a new template file when the existing file template is outdated
                     */

                    if (
                        $activeRevision &&
                        $fileTpl['universal'][$filename]['size'] === strlen($activeRevision->getMarkup()) &&
                        hash_file('crc32', $fileTpl['universal'][$filename]['template']->getPath()) === hash('crc32', $activeRevision->getMarkup())
                    ) {
                        // do nothing
                    } // create initial revision

                    else if (!$newestRevision && !$activeRevision) {

                        self::createPage($fileTpl['universal'][$filename]);

                    } // add new revision

                    else if (
                        ($activeRevision && $activeRevision->getFirstCreated()->getTimestamp() < $fileTpl['universal'][$filename]['fmtime']) ||
                        ($newestRevision && $newestRevision->getFirstCreated()->getTimestamp() < $fileTpl['universal'][$filename]['fmtime'])
                    ) {

                        // add revision

                        if ($activeRevision) {
                            $newRevision = clone $activeRevision;
                        } else {
                            $newRevision = clone $newestRevision;
                        }

                        $newRevision
                            ->setMarkup(file_get_contents($fileTpl['universal'][$filename]['template']->getPath()))
                            ->activate()
                            ->save();

                    } // store revision id to create a file template

                    else {

                        $pagesToExport[] = $page;

                    }

                    // remove the file template from the map

                    unset($fileTpl['universal'][$filename]);

                }

            }

            // create db entries which are not present in the database

            foreach ($fileTpl as $templates) {

                foreach ($templates as $template) {
                    self::createPage($template);
                }

            }

            // create files by retrieving revision data

            foreach ($pagesToExport as $page) {
                $page->exportActiveRevision();
            }

        }

        /**
         * retrieve file templates for a given locale
         *
         * @param array $fileTemplates
         * @param Locale|null $locale
         * @return void
         * @throws \Throwable
         */
        private static function getTplFiles(array &$fileTemplates, ?Locale $locale = null): void
        {
            $app = Application::getInstance();
            $config = $app->getConfig();

            if (is_null($config->paths['editable_tpl_path'])) {
                throw new \RuntimeException('No path for templates defined.');
            }

            $ndx = $locale ? $locale->getLocaleId() : 'universal';
            $subdir = $locale ? $locale->getLocaleId() . DIRECTORY_SEPARATOR : '';
            $path = rtrim($app->getRootPath(), DIRECTORY_SEPARATOR) . $config->paths['editable_tpl_path']['subdir'] . $subdir;

            try {
                foreach (FilesystemFolder::getInstance($path)->getFiles('php') as $f) {

                    $fi = $f->getFileInfo();

                    $fileTemplates[$ndx][$f->getFilename()] = [
                        'template' => $f,
                        'fmtime' => $fi->getMTime(),
                        'size' => $fi->getSize()
                    ];
                }
            } catch (FilesystemFolderException) {
            }
        }

        /**
         * create a page and a first revision
         * if the page already exists, just a revision is added
         *
         * @param array $data template data
         * @return void
         * @throws \Throwable
         */
        private static function createPage(array $data): void
        {
            $db = Application::getInstance()->getVxPDO();

            $alias = strtoupper(basename($data['template']->getFilename(), '.php'));

            $rows = $db->doPreparedQuery("SELECT pagesid from pages WHERE alias = ?", [$alias]);

            // insert only revision (locale might differ)

            if (count($rows)) {
                $page = Page::getInstance((int)$rows->current()['pagesid']);
            } else {
                $newId = $db->insertRecord('pages',
                    [
                        'Template' => $data['template']->getFilename(),
                        'Alias' => $alias
                    ]
                );
                $page = Page::getInstance((int)$newId);
            }

            $creationDate = new \DateTime();
            $creationDate->setTimestamp((int)$data['fmtime']);

            $revision = new Revision($page);
            $revision
                ->setMarkup(file_get_contents($data['template']->getPath()))
                ->setFirstCreated($creationDate)
                ->activate()
                ->save();
        }
    }