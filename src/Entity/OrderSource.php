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
 * @ORM\Entity(repositoryClass="App\Repository\OrderSourceRepository")
 * @ORM\Table(name="`order_source`")
 **/
class OrderSource extends BaseEntity
{
    const TYPE_FIRST_CLICK = 'first_click';
    const TYPE_LAST_CLICK = 'last_click';
    const TYPE_ASSISTED_CLICK = 'assisted_click';

    /**
     * @var IntegrationService
     * @ORM\ManyToOne(targetEntity="IntegrationService")
     * @ORM\JoinColumn(name="integration_id", referencedColumnName="id", nullable=false)
     */
    protected $integration;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var string
     * @ORM\Column(name="order_id", type="string")
     */
    protected $orderId;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $medium;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $campaign;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keyword;

    /**
     * @var integer
     * @ORM\Column(type="float")
     */
    protected $amount = 0;

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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     */
    public function setOrderId(string $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getMedium(): string
    {
        return $this->medium;
    }

    /**
     * @param string $medium
     */
    public function setMedium(string $medium)
    {
        $this->medium = $medium;
    }

    /**
     * @return string
     */
    public function getCampaign(): string
    {
        return $this->campaign;
    }

    /**
     * @param string $campaign
     */
    public function setCampaign(string $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword(string $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }
}