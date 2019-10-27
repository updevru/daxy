<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 16.09.2016
 * Time: 9:27
 */

namespace App\Service\Integration\RetailCRM\Dto;

class CustomerSegment
{
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $title;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @author Ladygin Sergey
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @author Ladygin Sergey
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}