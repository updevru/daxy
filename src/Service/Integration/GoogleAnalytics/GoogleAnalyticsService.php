<?php

namespace App\Service\Integration\GoogleAnalytics;

use App\Service\Integration\IntegrationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormInterface;
use App\Entity\OrderSource;

class GoogleAnalyticsService implements IntegrationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \App\Entity\IntegrationService
     */
    private $integrationService;

    /**
     * @var \Google_Service_Analytics
     */
    private $api;

    public function __construct(\App\Entity\IntegrationService $integrationService, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->integrationService = $integrationService;
    }

    public function createForm(FormInterface $form): FormInterface
    {
        $form->add(
            'settings_viewId',
            TextType::class,
            ['label' => 'View ID']
        );
        $form->add(
            'settings_key',
            TextareaType::class,
            ['label' => 'Key']
        );

        $form->add(
            'settings_step',
            ChoiceType::class,
            [
                'label' => 'Step',
                'choices' => [
                    'day' => 'day',
                    'week' => 'week',
                    'month' => 'month'
                ]
            ]
        );

        $form->add(
            'settings_date_start',
            DateType::class,
            [
                'label' => 'Date start',
                'input' => 'string'
            ]
        );

        return $form;
    }

    public function execute(Output $output)
    {
        $settings = $this->integrationService->getSettings();
        $client = new \Google_Client();
        $client->setApplicationName("Analytics Reporting");
        $client->setAuthConfig(json_decode($settings['key'], true));
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->api = new \Google_Service_Analytics($client);

        $this->importAssistedConversions($settings['step'], new \DateTime($settings['date_start']), $output);
        $settings['date_start'] = (new \DateTime())->format('Y-m-d');
        $this->integrationService->setSettings($settings);
        $this->em->flush();
    }

    protected function importAssistedConversions($step, \DateTime $dateStart, Output $output)
    {
        $orderRepository = $this->em->getRepository(OrderSource::class);

        $start = $dateStart;
        $end = new \DateTime();

        $dateStep = clone $start;
        $dateStep->modify('next ' . $step);

        if($dateStep->getTimestamp() > $end->getTimestamp()) {
            $dateStep = clone $end;
        }

        while (true) {
            $insert = 0;

            $output->writeln(
                sprintf('Fetch data from Google Analytics at %s to %s', $start->format('c'), $dateStep->format('c'))
            );
            $result = $this->getAssistedConversions($start, $dateStep);
            $orderRepository->cleanByDate($this->integrationService, clone $start, clone $dateStep);

            foreach ($result as $row) {

                $rowTypes = [];
                if($row['assistedConversions'] > 0) {
                    $rowTypes[OrderSource::TYPE_ASSISTED_CLICK] = $row['assistedValue'];
                }

                if($row['firstInteractionConversions'] > 0) {
                    $rowTypes[OrderSource::TYPE_FIRST_CLICK] = $row['firstInteractionValue'];
                }

                if($row['lastInteractionConversions'] > 0) {
                    $rowTypes[OrderSource::TYPE_LAST_CLICK] = $row['lastInteractionValue'];
                }

                //20181106
                $date = new \DateTime();
                $date->setDate(
                    substr($row['conversionDate'], 0, 4),
                    substr($row['conversionDate'], 4, 2),
                    substr($row['conversionDate'], 6, 2)
                );
                $date->setTime(0, 0, 0);

                foreach ($rowTypes as $type => $amount) {
                    $item = new OrderSource();
                    $item->setIntegration($this->integrationService);
                    $item->setType($type);
                    $item->setDate(clone  $date);
                    $item->setOrderId($row['transactionId']);
                    $item->setAmount($amount);
                    $item->setSource($row['source']);
                    $item->setMedium($row['medium']);
                    $item->setCampaign($row['campaignName']);
                    $item->setKeyword($row['keyword']);
                    $this->em->persist($item);
                    $insert++;
                }
            }

            $this->em->flush();
            $output->writeln(
                sprintf('At %s to %s: save %s', $start->format('Y-m-d'), $dateStep->format('Y-m-d'), $insert)
            );

            if($dateStep->getTimestamp() >= $end->getTimestamp()) {
                break;
            }

            $dateStep->modify('next ' . $step);
            $start->modify('next ' . $step);
        }
    }

    private function getMcfResults(\Google_Service_Analytics_McfData $collection)
    {
        $columns = [];
        foreach ($collection->getColumnHeaders() as $row) {
            $columns[] = str_replace('mcf:', '', $row['name']);
        }

        $rows = [];
        /** @var \Google_Service_Analytics_McfDataRows $row */
        foreach ($collection->getRows() as $row) {
            $item = [];
            foreach ($row['modelData'] as $i => $val) {
                $item[$columns[$i]] = $val['primitiveValue'];
            }
            $rows[] = $item;
        }

        return $rows;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return array
     */
    public function getAssistedConversions(\DateTime $start, \DateTime $end)
    {
        $results = $this->api->data_mcf->get(
            'ga:' . $this->integrationService->getSettings()['viewId'],
            $start->format('Y-m-d'),
            $end->format('Y-m-d'),
            'mcf:firstInteractionConversions,mcf:firstInteractionValue,mcf:lastInteractionConversions,mcf:lastInteractionValue,mcf:assistedConversions,mcf:assistedValue',
            [
                'dimensions' => 'mcf:source,mcf:medium,mcf:campaignName,mcf:keyword,mcf:transactionId,mcf:conversionType,mcf:conversionDate',
                'filters' => 'mcf:conversionType==Transaction'
            ]
        );

        return $this->getMcfResults($results);
    }
}