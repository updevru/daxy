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
 * @ORM\Entity(repositoryClass="\App\Repository\ProjectRepository")
 * @ORM\Table(name="project")
 **/
class Project extends BaseEntity
{
    /**
     * @var User[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="projects")
     */
    protected $users;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return User[]|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
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
}