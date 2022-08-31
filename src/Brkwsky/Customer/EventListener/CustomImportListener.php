<?php

declare(strict_types=1);

/**
 * @package       Customer HPS Hettich
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @license       commercial
 */

namespace Brkwsky\Customer\EventListener;

use Brkwsky\Jobs\Model\JobOfferModel;
use Brkwsky\JobsImport\Classes\Model\Import;
use Brkwsky\JobsImport\Event\CustomImportEvent;
use Contao\StringUtil;

class CustomImportListener
{
    protected Import $import;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    public function onBrkwskyJobsImportCustom(CustomImportEvent $event): void
    {
        $settings = $event->getImportSettings();
        $time = time();

        if ('staffexpert' === $settings['importAlias']) {
            $jobs = $this->import->getJobsFromXMLFileWithXPath(TL_ROOT.'/'.$settings['xml_feed'], $settings['xPath']);

            foreach ($jobs as $job) {
                $jobId = (string) $job->AdId;
                $objJob = JobOfferModel::findOneBy(['source = ?', 'sourceKey = ?', 'root = ?'], [$settings['source'], $jobId, $settings['jobsRootId']]);
                if (!$objJob) {
                    $objJob = new JobOfferModel();
                    $objJob->source = $settings['source'];
                    $objJob->sourceKey = $jobId;
                    $objJob->root = $settings['jobsRootId'];
                }

                $objJob->start = strtotime((string) $job->AdStart);
                $objJob->stop = strtotime((string) $job->AdExpires);
                $objJob->jobCity = (string) $job->Einsatzort;

                $xmlJobTypes = $job->xpath('Stellenart_Detailed');
                $jobTypes = StringUtil::deserialize($settings['mapper_jobTypes']);
                $types = [];

                foreach ($xmlJobTypes as $xmlJobType) {
                    foreach (($xmlJobType->xpath('Property')) as $property) {
                        foreach ($jobTypes as $type) {
                            if ($type['foreignID'] === (string) $property->Id) {
                                if (!\in_array($type['jobType'], $types, true)) {
                                    $types[] = $type['jobType'];
                                }
                                break;
                            }
                        }
                    }
                }

                if (empty($types)) {
                    $types[] = $settings['jobTypeFallback'];
                }

                $xmlCategories = $job->xpath('Branche_Detailed');
                $categories = StringUtil::deserialize($settings['mapper_categories']);
                $jobCategories = [];

                foreach ($xmlCategories as $xmlCategory) {
                    foreach (($xmlCategory->xpath('Property')) as $property) {
                        foreach ($categories as $category) {
                            if ($category['foreignID'] === (string) $property->Id) {
                                if (!\in_array($category['category'], $jobCategories, true)) {
                                    $jobCategories[] = $category['category'];
                                }
                                break;
                            }
                        }
                    }
                }

                $this->import->writeMappedCategories($jobCategories, $objJob, $settings['categoryFallback']);

                $objJob->type = serialize($types);

                $objJob->name = (string) $job->Positionstext;
                $objJob->description = StringUtil::decodeEntities((string) $job->Homepage);
                $objJob->published = $settings['published'];
                $this->import->generateAlias($objJob);

                $objJob->imported = $settings['importAlias'];

                if ($settings['setGeoData']) {
                    $this->import->setGeo($objJob, $settings['googleMapsAPIKey']);
                }

                if ($settings['author']) {
                    $objJob->author = $settings['author'];
                }

                if ($objJob->isModified()) {
                    $objJob->tstamp = $time;
                    $objJob->publishedDate = $time;
                }

                $objJob->importTimestamp = $time;

                $objJob->save();
            }

            $this->import->deleteOldJobs($settings['importAlias'], $time);
        }
    }
}
