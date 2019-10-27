<?php

namespace App\Service\Integration\CostManualImport;

use App\Service\Integration\IntegrationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use App\Entity\Cost;

class CostManualImportService implements IntegrationInterface
{
    const NAME = 'COST_IMPORT_MANUAL';

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
            'settings_custom_coefficient',
            TextType::class,
            ['label' => 'Apply custom coefficient to cost', 'required' => false]
        );

        $form->add(
            'settings_add_tax',
            CheckboxType::class,
            ['label' => 'Add tax to cost', 'required' => false]
        );

        $form->add(
            'settings_custom_percent',
            PercentType::class,
            ['label' => 'Add percent to cost', 'required' => false]
        );

        $form->add(
            'settings_utm_source',
            TextType::class,
            ['label' => 'Default utm_source', 'required' => false]
        );

        $form->add(
            'settings_utm_medium',
            TextType::class,
            ['label' => 'Default utm_medium', 'required' => false]
        );

        return $form;
    }

    public function execute(Output $output)
    {

    }
}