<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_job_offer']['fields']['moneyExtended'] = [
    'exclude' => true,
    'inputType' => 'text',
    'eval' => [
        'maxlength' => 255,
        'tl_class' => 'long clr'
    ],
    'sql' => "varchar(255) NOT NULL default ''",
];

PaletteManipulator::create()
    ->addField('moneyExtended', 'money')
    ->applyToPalette('default', 'tl_job_offer')
;

PaletteManipulator::create()
    ->addField('moneyExtended', 'money')
    ->applyToPalette('create', 'tl_job_offer')
;

PaletteManipulator::create()
    ->addField('moneyExtended', 'money')
    ->applyToPalette('edit', 'tl_job_offer')
;