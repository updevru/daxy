<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.07.2019
 * Time: 1:00
 */

namespace App\Service;

use App\Entity\AdvertSystem;
use App\Entity\Cost;
use App\Entity\IntegrationService;
use App\Entity\Project;
use App\Entity\User;
use App\Form\Value\ReportSalesValue;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use League\Csv\Reader;

class ReportService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param ReportSalesValue $value
     * @return Report\Result|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function orderSales(ReportSalesValue $value)
    {
        $project = $value->getProject();
        $dateFrom = $value->getDateFrom();
        $conversionType = $value->getConversionType();

        $dateTo = $value->getDateTo();
        $dateTo->modify('+1 day');

        $sql = "
SELECT
    source.*,
    sum(os.amount) as convert_amount,
    (SELECT sum(c.cost) FROM cost as c WHERE c.source = source.source AND c.medium = source.medium AND c.date >= :date_from AND c.date < :date_to) as advert_cost,
    (SELECT sum(c.views) FROM cost as c WHERE c.source = source.source AND c.medium = source.medium AND c.date >= :date_from AND c.date < :date_to) as advert_views,
    (SELECT sum(c.clicks) FROM cost as c WHERE c.source = source.source AND c.medium = source.medium AND c.date >= :date_from AND c.date < :date_to) as advert_clicks,
    count(o.id) as order_count,
    sum(o.summ) as order_sum,
    sum(o.delivery_summ) as order_delivery_summ,
    sum(o.delivery_cost) as order_delivery_cost,
    sum(o.product_cost) as order_product_cost,
    sum(o.profit) as order_profit,
    sum(CASE WHEN o.`status` IN ('complete') AND o.id IS NOT NULL THEN 1 ELSE 0 END) AS order_complete_count,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.summ ELSE 0 END) AS order_complete_sum,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.delivery_summ ELSE 0 END) AS order_complete_delivery_summ,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.delivery_cost ELSE 0 END) AS order_complete_delivery_cost,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.product_cost ELSE 0 END) AS order_complete_product_cost,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.profit ELSE 0 END) AS order_complete_profit
FROM (
	(SELECT os.source, os.medium FROM order_source os WHERE os.date >= :date_from AND os.date < :date_to AND os.integration_id IN (SELECT i.id FROM integration_service as i WHERE i.project_id = :project) GROUP BY os.source, os.medium)
	UNION 
	(SELECT c.source, c.medium FROM cost c WHERE c.date >= :date_from AND c.date < :date_to AND c.integration_id IN (SELECT i.id FROM integration_service as i WHERE i.project_id = :project) GROUP BY c.source, c.medium)
    ) as source
LEFT JOIN order_source as os ON os.source = source.source AND os.medium = source.medium
LEFT JOIN `order` as o ON o.order_id = os.order_id
WHERE 
      os.date >= :date_from 
  AND os.date < :date_to 
  AND os.`type` = :conversionType 
  AND os.integration_id IN (SELECT i.id FROM integration_service as i WHERE i.project_id = :project)
  AND o.integration_id IN (SELECT i.id FROM integration_service as i WHERE i.project_id = :project)
GROUP BY source.source, source.medium";

        $query = $this->em->getConnection()->prepare($sql);
        $query->execute(
            [
                'date_from' => $dateFrom->format('Y-m-d'),
                'date_to' => $dateTo->format('Y-m-d'),
                'conversionType' => $conversionType,
                'project' => $project->getId(),
            ]
        );

        $result = new \App\Service\Report\SalesResult();
        if ($rows = $query->fetchAll()) {
            $result->setRows($rows);
        }

        $sql = "
SELECT
    count(o.id) as order_count,
    sum(o.summ) as order_sum,
    sum(o.delivery_summ) as order_delivery_summ,
    sum(o.delivery_cost) as order_delivery_cost,
    sum(o.product_cost) as order_product_cost,
    sum(o.profit) as order_profit,
    sum(CASE WHEN o.`status` IN ('complete') AND o.id IS NOT NULL THEN 1 ELSE 0 END) AS order_complete_count,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.summ ELSE 0 END) AS order_complete_sum,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.delivery_summ ELSE 0 END) AS order_complete_delivery_summ,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.delivery_cost ELSE 0 END) AS order_complete_delivery_cost,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.product_cost ELSE 0 END) AS order_complete_product_cost,
    sum(CASE WHEN o.`status` IN ('complete') THEN o.profit ELSE 0 END) AS order_complete_profit
FROM `order` as o
LEFT JOIN order_source as os ON o.order_id = os.order_id
WHERE 
      o.date_create >= :date_from 
  AND o.date_create < :date_to 
  AND os.id IS NULL
  AND o.integration_id IN (SELECT i.id FROM integration_service as i WHERE i.project_id = :project)
        ";

        $query = $this->em->getConnection()->prepare($sql);
        $query->execute(
            [
                'date_from' => $dateFrom->format('Y-m-d'),
                'date_to'   => $dateTo->format('Y-m-d'),
                'project'   => $project->getId(),
            ]
        );
        if ($row = $query->fetch()) {
            $result->addRow(
                array_merge(
                    [
                        'source'        => '',
                        'medium'        => '',
                        'advert_cost'   => 0,
                        'advert_views'  => 0,
                        'advert_clicks' => 0,
                    ],
                    $row
                )
            );
        }

        return $result;
    }
}