<?php

namespace App\Service\Report\ResultType;

class PercentType extends AbstractType
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return number_format($this->getValue() * 100, 2, '.', ' ').'%';
    }
}