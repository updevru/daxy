<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.11.2018
 * Time: 23:05
 */

namespace App\Repository;

use \App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function getProjectsByUser(User $user)
    {
        return $this->createQueryBuilder('p')
            ->join('p.users', 'usr')
            ->andWhere('usr.id = :user')->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
    }
}