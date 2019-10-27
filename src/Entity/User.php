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
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 **/
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Project[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="users")
     */
    protected $projects;

    public function __construct()
    {
        parent::__construct();

        $this->projects = new ArrayCollection();
    }

    /**
     * @return Project[]|ArrayCollection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}