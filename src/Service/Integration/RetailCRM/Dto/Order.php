<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 16.09.2016
 * Time: 9:27
 */

namespace App\Service\Integration\RetailCRM\Dto;

class Order {

    protected $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->data['externalId'];
    }

    public function getCustomerId()
    {
        return $this->data['customer']['id'];
    }

    public function getMethod()
    {
        return $this->data['orderMethod'];
    }

    public function getStatus()
    {
        return $this->data['status'];
    }

    public function getDateCreate()
    {
        return new \DateTime($this->data['createdAt']);
    }

    public function getTotalSumm()
    {
        return $this->data['totalSumm'];
    }

    public function getDeliverySumm()
    {
        return $this->data['delivery']['cost'];
    }

    public function getDeliveryCost()
    {
        return $this->data['delivery']['netCost'];
    }

    public function getProductCost()
    {
        return $this->data['purchaseSumm'];
    }

    public function getAllProductsSumm()
    {
        $total = 0;
        foreach ($this->data['items'] as $item) {
            $total += $item['initialPrice'];
        }

        return $total;
    }
}