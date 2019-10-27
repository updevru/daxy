<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.11.2018
 * Time: 21:59
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`cost`")
 **/
class Cost extends BaseEntity
{
    /**
     * @var IntegrationService
     * @ORM\ManyToOne(targetEntity="IntegrationService")
     * @ORM\JoinColumn(name="integration_id", referencedColumnName="id", nullable=false)
     */
    protected $integration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $cost = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $views = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $clicks = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $source;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $medium;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $campaign;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $keyword;

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
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
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
     * @return float
     */
    public function getCost(): float
    {
        return $this->cost;
    }

    /**
     * @param float $cost
     */
    public function setCost($cost)
    {
        $this->cost = (float) $cost;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views)
    {
        $this->views = $views;
    }

    /**
     * @return int
     */
    public function getClicks(): int
    {
        return $this->clicks;
    }

    /**
     * @param int $clicks
     */
    public function setClicks(int $clicks)
    {
        $this->clicks = $clicks;
    }

    /**
     * @return string
     */
    public function getSource(): ?string
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
    public function getMedium(): ?string
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
     * @return string|null
     */
    public function getCampaign(): ?string
    {
        return $this->campaign;
    }

    /**
     * @param string|null $campaign
     */
    public function setCampaign(?string $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @param string|null $keyword
     */
    public function setKeyword(?string $keyword)
    {
        $this->keyword = $keyword;
    }
}