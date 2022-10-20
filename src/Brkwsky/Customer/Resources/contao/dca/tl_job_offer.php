<?php

declare(strict_types=1);

/**
 * @package       Customer HPS Hettich
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_job_offer']['fields']['workingHours'] = [
    'inputType' => 'text',
    'eval' => ['maxlength' => 255],
    'sql' => "varchar(255) NOT NULL default ''",
];

PaletteManipulator::create()
    ->addField('workingHours', 'name_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_job_offer');
PaletteManipulator::create()
    ->addField('workingHours', 'description_fe_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('create', 'tl_job_offer')
    ->applyToPalette('edit', 'tl_job_offer');
