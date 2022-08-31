<?php

declare(strict_types=1);

/**
 * @package       Customer HPS Hettich
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_jobs_import']['fields']['author'] = [
    'exclude' => true,
    'inputType' => 'select',
    'foreignKey' => "tl_member.CONCAT(firstname, ' ', lastname, ' (', company, ')')",
    'sql' => 'int(10) unsigned NOT NULL default 0',
];

PaletteManipulator::create()
    ->addField('author', 'mapping_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_jobs_import')
;