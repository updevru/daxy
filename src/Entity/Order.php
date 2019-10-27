<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.11.2018
 * Time: 21:59
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 **/
class Order extends BaseEntity
{
    /**
     * @var IntegrationService
     * @ORM\ManyToOne(targetEntity="IntegrationService")
     * @ORM\JoinColumn(name="integration_id", referencedColumnName="id", nullable=false)
     */
    protected $integration;

    /**
     * @var integer
     * @ORM\Column(name="order_id", type="string")
     */
    protected $orderId;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @var integer
     * @ORM\Column(name="customer_id", type="string")
     */
    protected $customerId;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_create", type="datetime")
     */
    protected $dateCreate;

    /**
     * Сумма заказа
     * @var float
     * @ORM\Column(type="float")
     */
    protected $summ = 0;

    /**
     * Стоимость доставки
     * @var float
     * @ORM\Column(name="delivery_summ", type="float")
     */
    protected $deliverySumm = 0;

    /**
     * Себестоимость доставки
     * @var float
     * @ORM\Column(name="delivery_cost", type="float")
     */
    protected $deliveryCost = 0;

    /**
     * Себестоимость товаров
     * @var float
     * @ORM\Column(name="product_cost", type="float")
     */
    protected $productCost = 0;

    /**
     * Доход
     * @var float
     * @ORM\Column(type="float")
     */
    protected $profit = 0;

    /**
     * Порядковый номер заказа
     * @var integer
     * @ORM\Column(name="order_index", type="integer")
     */
    protected $orderIndex = 1;

    /**
     * @return IntegrationService
     */
    public function getIntegration()
    {
        return $this->integration;
    }

    /**
     * @param IntegrationService $integration
     */
    public function setIntegration(IntegrationService $integration)
    {
        $this->integration = $integration;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param int $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param int $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * @param \DateTime $dateCreate
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;
    }

    /**
     * @return float
     */
    public function getSumm()
    {
        return $this->summ;
    }

    /**
     * @param float $summ
     */
    public function setSumm($summ)
    {
        $this->summ = $summ;
    }

    /**
     * @return float
     */
    public function getDeliverySumm()
    {
        return $this->deliverySumm;
    }

    /**
     * @param float $deliverySumm
     */
    public function setDeliverySumm($deliverySumm)
    {
        $this->deliverySumm = $deliverySumm;
    }

    /**
     * @return float
     */
    public function getDeliveryCost()
    {
        return $this->deliveryCost;
    }

    /**
     * @param float $deliveryCost
     */
    public function setDeliveryCost($deliveryCost)
    {
        $this->deliveryCost = $deliveryCost;
    }

    /**
     * @return float
     */
    public function getProductCost()
    {
        return $this->productCost;
    }

    /**
     * @param float $productCost
     */
    public function setProductCost($productCost)
    {
        $this->productCost = $productCost;
    }

    /**
     * @return float
     */
    public function getProfit()
    {
        return $this->profit;
    }

    /**
     * @param float $profit
     */
    public function setProfit($profit)
    {
        $this->profit = $profit;
    }

    /**
     * @return int
     */
    public function getOrderIndex()
    {
        return $this->orderIndex;
    }

    /**
     * @param int $orderIndex
     */
    public function setOrderIndex($orderIndex)
    {
        $this->orderIndex = $orderIndex;
    }
}