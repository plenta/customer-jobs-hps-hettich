<?php

declare(strict_types=1);

/**
 * @package       Customer HPS Hettich
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Brkwsky\Customer\EventListener;

use Brkwsky\Jobs\Event\JobsExportLoopEvent;
use Contao\FrontendTemplate;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: JobsExportLoopEvent::NAME, method: 'onExportLoop')]
class ExportLoopListener
{
    public function onExportLoop(JobsExportLoopEvent $event): void
    {
        $settings = $event->getExportModel();

        if ('indeed' === $settings->type) {
            $offer = $event->getOffer();
            $tpl = new FrontendTemplate('_description_appendix');
            $offer->description .= $tpl->parse();
            $event->setOffer($offer);
            if (!empty($offer->moneyExtended)) {
                $appendix = $event->getAppendix();
                $appendix = '<salary><![CDATA['.$offer->moneyExtended.']]></salary>';
                $event->setAppendix($appendix);
            }
        }
    }
}
