<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 16.09.2016
 * Time: 9:27
 */

namespace App\Service\Integration\RetailCRM\Dto;

class CustomerOrders {

    /**
     * @var int
     */
    private $ordersCount;
    /**
     * @var bool
     */
    private $inWork;

    public function __construct($ordersCount, $inWork = false)
    {
        $this->ordersCount = $ordersCount;
        $this->inWork = $inWork;
    }

    /**
     * Количество успешных заказов
     *
     * @return int
     */
    public function getOrdersCount()
    {
        return $this->ordersCount;
    }

    /**
     * Есть ли заказы в работе
     *
     * @return bool
     */
    public function isInWork()
    {
        return $this->inWork;
    }
} 