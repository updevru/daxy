<?php

namespace App\Service\Integration\BigQuery;

use App\Service\StorageService;
use App\Service\Integration\IntegrationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;

class BigQueryService implements IntegrationInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var \App\Entity\IntegrationService
     */
    private $integrationService;

    public function __construct(\App\Entity\IntegrationService $integrationService, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->integrationService = $integrationService;
    }

    public function createForm(FormInterface $form): FormInterface
    {
        $form->add(
            'settings_projectId',
            TextType::class,
            ['label' => 'Project ID']
        );
        $form->add(
            'settings_datasetId',
            TextType::class,
            ['label' => 'Dataset name']
        );
        $form->add(
            'settings_key',
            TextareaType::class,
            ['label' => 'Key']
        );

        $list = [];
        foreach ($this->em->getConnection()->getSchemaManager()->listTableNames() as $name) {
            $list[$name] = $name;
        }

        $form->add(
            'settings_export_tables',
            ChoiceType::class,
            ['choices' => $list, 'multiple' => true]
        );

        return $form;
    }

    public function execute(Output $output)
    {
        $settings = $this->integrationService->getSettings();
        $bigQuery = new \App\Service\Storage\BigQuery(
            $settings['projectId'],
            $settings['datasetId'],
            json_decode($settings['key'], true)
        );

        if (!empty($settings['export_tables'])) {
            foreach ($settings['export_tables'] as $table) {
                $output->writeln(sprintf('Start import table: %s', $table));

                $storage = new StorageService($bigQuery, $table);
                $this->importDataFromTables($output, $storage, $table);

                $output->writeln(sprintf('End import table: %s', $table));
            }
        }
    }

    private function importDataFromTables(Output $output, StorageService $storage, $table)
    {
        $limit = 1000;
        $offset = 0;
        while (true) {
            $sql = sprintf("SELECT * FROM %s LIMIT %s OFFSET %s", $table, $limit, $offset);
            $output->writeln("Fetch " . $sql);
            if (!$data = $this->em->getConnection()->fetchAll($sql)) {
                break;
            }

            foreach ($data as $row) {
                $storage->store($row);
            }

            $storage->flush();
            $output->writeln('Flush data');
            $offset += $limit;
        }

        return true;
    }
}