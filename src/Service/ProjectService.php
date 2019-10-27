<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.07.2019
 * Time: 1:00
 */

namespace App\Service;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ProjectService
{
    /**
     * @var \App\Repository\ProjectRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Project::class);
    }

    /**
     * @param User $user
     *
     * @return Project[]|array|object[]
     */
    public function getProjectsByUser(User $user)
    {
        return $this->repository->getProjectsByUser($user);
    }
}