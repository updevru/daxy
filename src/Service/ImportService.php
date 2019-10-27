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
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

class ImportService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function importTrafficCost($csvFile, IntegrationService $integrationService)
    {
        $reader = Reader::createFromPath($csvFile);
        $reader->setHeaderOffset(0);
        //$reader->setDelimiter(';');

        $repository = $this->entityManager->getRepository(Cost::class);
        foreach ($reader->getRecords() as $record) {
            $date = new \DateTime($record['date']);
            if (!$row = $repository->findOneBy(['integration' => $integrationService, 'date' => $date])) {
                $row = new Cost();
                $row->setIntegration($integrationService);
                $row->setDate(new \DateTime($record['date']));
            }

            $row->setViews((!empty($record['views'])) ? $this->toInt($record['views']) : 0);
            $row->setClicks((!empty($record['clicks'])) ? $this->toInt($record['clicks']) : 0);
            $row->setCost($this->toFloat($record['cost']));
            $row->setSource(
                (!empty($record['source'])) ? $record['source'] : $integrationService->getSetting('utm_source')
            );
            $row->setMedium(
                (!empty($record['medium'])) ? $record['medium'] : $integrationService->getSetting('utm_medium')
            );
            $row->setCampaign((!empty($record['campaign'])) ? $record['campaign'] : null);
            $row->setKeyword((!empty($record['keyword'])) ? $record['keyword'] : null);

            if ($integrationService->getSetting('custom_coefficient') !== null) {
                $this->applyCustomCoefficient($row, $this->toFloat($integrationService->getSetting('custom_coefficient')));
            }

            if ($integrationService->getSetting('add_tax')) {
                $this->applyTax($row);
            }

            if ($integrationService->getSetting('custom_percent') !== null) {
                $this->applyCustomPercent($row, $integrationService->getSetting('custom_percent'));
            }

            $this->entityManager->persist($row);
        }

        $this->entityManager->flush();
    }

    /**
     * @param mixed $value
     * @return float
     */
    private function toFloat($value)
    {
        return (float) str_replace(',', '.', $value);
    }

    /**
     * @param mixed $value
     *
     * @return int
     */
    private function toInt($value)
    {
        if (is_int($value)) {
            return $value;
        }

        return (int) str_replace(' ', '', $value);
    }

    /**
     * @param Cost $cost
     */
    private function applyTax(Cost $cost)
    {
        if ($cost->getDate()->format('Y') >= 2019) {
            $tax = 20;
        } else {
            $tax = 18;
        }

        $total = $cost->getCost() + $cost->getCost() * ($tax/100);
        $cost->setCost($total);
    }

    /**
     * @param Cost $cost
     * @param float $percent
     */
    private function applyCustomPercent(Cost $cost, $percent)
    {
        $total = $cost->getCost() + $cost->getCost() * $percent;
        $cost->setCost($total);
    }

    /**
     * @param Cost $cost
     * @param float $coefficient
     */
    private function applyCustomCoefficient(Cost $cost, $coefficient)
    {
        $total = $cost->getCost() * $coefficient;
        $cost->setCost($total);
    }
}