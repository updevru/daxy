<?php

namespace App\Service\Report;

use App\Service\Report\ResultType\AbstractType;
use App\Service\Report\ResultType\ResultTypeInterface;
use Doctrine\Common\Collections\ArrayCollection;

class ResultRow implements \ArrayAccess
{
    /**
     * @var ArrayCollection
     */
    private $columns;

    /**
     * @var array
     */
    private $row;

    public function __construct(ArrayCollection $columns, array $row)
    {
        $this->columns = $columns;
        $this->row = $row;
    }

    /**
     * @param string $name
     *
     * @return ResultTypeInterface
     * @throws \Exception
     */
    public function get(string $name)
    {
        if (empty($this->columns->get($name))) {
            throw new \Exception(sprintf('Column %s not found', $name));
        }

        if (!$this->offsetExists($name)) {
            throw new \Exception(sprintf('Data with index %s not found', $name));
        }

        return AbstractType::factory(
            $this->columns->get($name)['type'],
            $name,
            $this->row
        );
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->row) || $this->columns->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception('Object is readonly');
    }

    public function offsetUnset($offset)
    {
        throw new \Exception('Object is readonly');
    }
}