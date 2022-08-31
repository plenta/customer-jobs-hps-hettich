<?php

declare(strict_types=1);

/**
 * @package       Customer HPS Hettich
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Brkwsky\Customer\Controller;

use Brkwsky\JobsImport\Classes\Import;
use Brkwsky\JobsImport\Classes\Model\Import as ImportModel;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\Input;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @Route("_jobs/hps/import")
 * @ServiceTag("controller.service_arguments")
 */
class ImportController extends AbstractController
{
    protected Import $import;
    protected ImportModel $importModel;

    public function __construct(Import $import, ImportModel $importModel)
    {
        $this->import = $import;
        $this->importModel = $importModel;
    }

    public function __invoke()
    {
        $this->container->get('contao.framework')->initialize();
        $id = Input::get('import');
        if ($id) {
            $settings = $this->importModel->getSettings($id);
            if ($settings && 'staffexpert' === $settings['importAlias']) {
                $this->import->import($id);

                return new Response('Jobs wurden importiert.');
            }
        }

        return new Response('Ungültige Konfiguration.');
    }
}
