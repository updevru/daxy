<?php

namespace App\Form\Type;

use App\Entity\OrderSource;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReportSalesType extends AbstractType
{
    /**
     * @var User
     */
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'project',
                EntityType::class,
                [
                    'class'         => Project::class,
                    'choice_label'  => 'title',
                    'label'         => 'Проект',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->join('i.users', 'u')
                            ->andWhere('u.id = :usr')->setParameter('usr', $this->user->getId());
                    },
                ]
            )
            ->add('dateFrom', DateType::class, ['label' => 'C'])
            ->add('dateTo', DateType::class, ['label' => 'по'])
            ->add(
                'conversion_type',
                ChoiceType::class,
                [
                    'label' => 'Conversion type',
                    'choices' => [
                        OrderSource::TYPE_FIRST_CLICK => OrderSource::TYPE_FIRST_CLICK,
                        OrderSource::TYPE_LAST_CLICK => OrderSource::TYPE_LAST_CLICK,
                        OrderSource::TYPE_ASSISTED_CLICK => OrderSource::TYPE_ASSISTED_CLICK,
                    ]
                ]
            )
            ->add('save', SubmitType::class, ['label' => 'Filter']);
    }
}