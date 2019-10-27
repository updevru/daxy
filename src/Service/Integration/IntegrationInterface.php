<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.07.2019
 * Time: 1:00
 */

namespace App\Service\Integration;

use App\Entity\IntegrationService as IntegrationServiceEntity;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Form\FormInterface;

interface IntegrationInterface
{
    /**
     * @param FormInterface $form
     *
     * @return FormInterface
     */
    public function createForm(FormInterface $form) : FormInterface;

    public function execute(Output $output);
}