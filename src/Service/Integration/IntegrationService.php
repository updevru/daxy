<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.07.2019
 * Time: 1:00
 */

namespace App\Service\Integration;

use App\Entity\IntegrationService as IntegrationServiceEntity;
use Doctrine\ORM\EntityManagerInterface;

class IntegrationService
{
    protected $services;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \App\Repository\IntegrationServiceRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    public function __construct($services, EntityManagerInterface $em)
    {
        foreach ($services as $service) {
            $this->add($service['code'], $service['class']);
        }
        $this->em = $em;
        $this->repository = $em->getRepository(IntegrationServiceEntity::class);
    }

    public function add($code, $class)
    {
        $this->services[$code] = $class;

        return $this;
    }

    /**
     * @param IntegrationServiceEntity $integrationService
     *
     * @return IntegrationInterface
     */
    public function get(IntegrationServiceEntity $integrationService)
    {
        if (!isset($this->services[$integrationService->getType()->getCode()])) {
            throw new \Exception(sprintf('Service %s not found', $integrationService->getType()->getCode()));
        }

        $class = $this->services[$integrationService->getType()->getCode()];
        return new $class($integrationService, $this->em);
    }

    /**
     * @param string $id
     *
     * @return IntegrationServiceEntity|null
     */
    public function findById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return IntegrationServiceEntity[]
     */
    public function findFoWork()
    {
        return $this->repository->findFoWork();
    }
}