<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.11.2018
 * Time: 23:05
 */

namespace App\Repository;

use \App\Entity\IntegrationService;
use Doctrine\ORM\EntityRepository;

class IntegrationServiceRepository extends EntityRepository
{
    /**
     * @return IntegrationService[]
     * @throws \Exception
     */
    public function findFoWork()
    {
        $query = $this->createQueryBuilder('s')
            ->andWhere('s.dateNext <= :date')
            ->andWhere('s.enabled = :enabled')
        ;

        $query->setParameters([
            'date' => new \DateTime(),
            'enabled' => true
        ]);

        return $query->getQuery()->getResult();
    }
}