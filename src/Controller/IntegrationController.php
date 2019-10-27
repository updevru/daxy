<?php

namespace App\Controller;

use App\Service\Integration\IntegrationService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class IntegrationController extends EasyAdminController
{
    /**
     * @var IntegrationService
     */
    private $integrationService;

    public function __construct(IntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    protected function createEditForm($entity, array $entityProperties)
    {
        $form = parent::createEditForm($entity, $entityProperties);

        return $this->integrationService->get($entity)->createForm($form);
    }
}