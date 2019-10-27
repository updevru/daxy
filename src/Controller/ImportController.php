<?php

namespace App\Controller;

use App\Form\Type\ImportFormType;
use App\Service\ImportService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportController extends EasyAdminController
{
    /**
     * @var ImportService
     */
    private $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    protected function listAction()
    {
        $form = $this->createForm(ImportFormType::class);
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->getData()['file'];
            $integration = $form->getData()['integration'];
            $this->importService->importTrafficCost($file->getRealPath(), $integration);
        }

        return $this->render(
            'admin/import.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}