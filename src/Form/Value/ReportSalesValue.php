<?php

namespace App\Form\Value;

use App\Entity\OrderSource;
use App\Entity\Project;

class ReportSalesValue
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var \DateTime|null
     */
    private $dateFrom;

    /**
     * @var \DateTime|null
     */
    private $dateTo;

    /**
     * @var string
     */
    private $conversionType;

    public function __construct()
    {
        $this->setConversionType(OrderSource::TYPE_ASSISTED_CLICK);
        $this->setDateFrom(new \DateTime('-30 day'));
        $this->setDateTo(new \DateTime());
    }

    /**
     * @return Project|null
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param \DateTime $dateFrom
     */
    public function setDateFrom(\DateTime $dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param \DateTime $dateTo
     */
    public function setDateTo(\DateTime $dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    /**
     * @return string
     */
    public function getConversionType()
    {
        return $this->conversionType;
    }

    /**
     * @param string $conversionType
     */
    public function setConversionType(string $conversionType): void
    {
        $this->conversionType = $conversionType;
    }
}