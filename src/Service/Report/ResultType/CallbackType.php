<?php

namespace App\Service\Report\ResultType;

class CallbackType extends AbstractType
{
    /**
     * @var \Closure
     */
    private $closure;

    private $type;

    public function __construct(\Closure $closure, $type)
    {
        $this->closure = $closure;
        $this->type = $type;
    }

    public function getValue()
    {
        return $this->closure->__invoke($this->getRow());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->create($this->type);
    }
}