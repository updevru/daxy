<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.11.2018
 * Time: 23:05
 */

namespace App\Repository;

use App\Entity\IntegrationService;
use \App\Entity\OrderSource;
use Doctrine\ORM\EntityRepository;

class OrderSourceRepository extends EntityRepository
{
    /**
     * @param IntegrationService $service
     * @param string $orderId
     *
     * @return OrderSource[]
     */
    public function getByOrderId(IntegrationService $service, $orderId)
    {
        return $this->findBy(['integration' => $service, 'orderId' => $orderId]);
    }

    /**
     * @param IntegrationService $service
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     */
    public function cleanByDate(IntegrationService $service, \DateTime $dateStart, \DateTime $dateEnd)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->delete(OrderSource::class, 'o')
            ->andWhere('o.integration = :integration')
            ->andWhere('o.date >= :dateStart')
            ->andWhere('o.date < :dateEnd');

        $query->setParameters(
            [
                'integration' => $service,
                'dateStart' => $dateStart->setTime(0, 0, 0),
                'dateEnd' => $dateEnd->modify('+1 day')->setTime(0, 0, 0),
            ]
        );

        $query->getQuery()->execute();
    }
}