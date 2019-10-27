<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 06.11.2018
 * Time: 21:59
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntegrationServiceRepository")
 * @ORM\Table(name="integration_service")
 * @ORM\HasLifecycleCallbacks
 **/
class IntegrationService extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var IntegrationServiceType
     *
     * @ORM\ManyToOne(targetEntity="IntegrationServiceType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $settings = [];

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;

    /**
     * @var string
     * @ORM\Column(type="string", options={"default" : "day"})
     */
    private $period = 'day';

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_start", type="datetime", nullable=true)
     */
    private $dateStart;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_next", type="datetime", nullable=true)
     */
    private $dateNext;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_started", type="datetime", nullable=true)
     */
    private $dateStarted;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="date_finished", type="datetime", nullable=true)
     */
    private $dateFinished;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $log;

    public function __construct()
    {
        $this->dateStart = new \DateTime();
        $this->dateNext = new \DateTime();
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if (strpos($name,'settings_', 0) !== false) {
            $name = str_replace('settings_', '', $name);
            return $this->settings[$name] ?? null;
        }

        return null;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        if (strpos($name,'settings_', 0) !== false) {
            $name = str_replace('settings_', '', $name);
            $this->settings[$name] = $value;
            return;
        }

        throw new \Exception('Use set method for property ' . $name);
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if ($next = $this->calculateDateNext()) {
            $this->dateNext = $next;
        }
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        if ($args->hasChangedField('period') || $args->hasChangedField('dateStart')) {
            $this->dateNext = null;
            if ($next = $this->calculateDateNext()) {
                $this->dateNext = $next;
            }
        }
    }

    /**
     * @return \DateTime|null
     */
    public function calculateDateNext()
    {
        if ($this->period && $this->dateNext instanceof \DateTime) {
            $next = clone $this->dateNext;
            $next->modify('+1 ' . $this->period);

            $now = new \DateTime();
            if ($next->getTimestamp() < $now->getTimestamp()) {
                $now->setTime($next->format('h'), $next->format('m'), $next->format('i'));
                $now->modify('+1 ' . $this->period);
                return $now;
            }

            return $next;
        } else if ($this->period && $this->dateStart instanceof \DateTime) {
            $next = clone $this->dateStart;
            return $next->modify('+1 ' . $this->period);
        }

        return null;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return IntegrationServiceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param IntegrationServiceType $type
     */
    public function setType(IntegrationServiceType $type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param array $settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getSetting(string $name)
    {
        return $this->settings[$name] ?? null;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param string $period
     */
    public function setPeriod(string $period)
    {
        $this->period = $period;
    }

    /**
     * @return \DateTime
     */
    public function getDateNext()
    {
        return $this->dateNext;
    }

    /**
     * @param \DateTime $dateNext
     */
    public function setDateNext(\DateTime $dateNext)
    {
        $this->dateNext = $dateNext;
    }

    /**
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @param \DateTime $dateStart
     */
    public function setDateStart(\DateTime $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateStarted()
    {
        return $this->dateStarted;
    }

    /**
     * @param \DateTime $dateStarted
     */
    public function setDateStarted(\DateTime $dateStarted)
    {
        $this->dateStarted = $dateStarted;
    }

    /**
     * @return \DateTime
     */
    public function getDateFinished()
    {
        return $this->dateFinished;
    }

    /**
     * @param \DateTime|null $dateFinished
     */
    public function setDateFinished(\DateTime $dateFinished = null)
    {
        $this->dateFinished = $dateFinished;
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @param string|null $log
     */
    public function setLog(?string $log)
    {
        $this->log = $log;
    }
}