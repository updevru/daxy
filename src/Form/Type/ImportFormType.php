<?php

namespace App\Form\Type;

use App\Entity\IntegrationService;
use App\Entity\User;
use App\Service\Integration\CostManualImport\CostManualImportService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class ImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('integration', EntityType::class, [
                'class' => IntegrationService::class,
                'choice_label' => 'title',
                'label' => 'Интеграция',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->join('i.type', 't')
                        ->andWhere('t.code = :code')->setParameter('code', CostManualImportService::NAME);
                },
            ])
            ->add('file', FileType::class, ['label' => 'Import from .csv'])
            ->add('save', SubmitType::class, ['label' => 'Import'])
        ;
    }
}