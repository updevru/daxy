<?php


namespace App\Service\Report\ResultType;


abstract class AbstractType implements ResultTypeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $row;

    public function setData(string $name, array $row)
    {
        $this->name = $name;
        $this->row = $row;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->row[$this->name];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRow(): array
    {
        return $this->row;
    }

    /**
     * @param string|ResultTypeInterface $type
     * @param string $name
     * @param array $row
     *
     * @return ResultTypeInterface
     */
    public static function factory($type, string $name, array $row)
    {
        if (is_string($type)) {
            $type = new $type();
        }

        return $type->setData($name, $row);
    }

    /**
     * @param string|ResultTypeInterface $type
     * @param $value
     *
     * @return ResultTypeInterface
     */
    public function create($type)
    {
        $this->row[$this->getName()] = $this->getValue();

        return static::factory($type, $this->getName(), $this->getRow());
    }
}