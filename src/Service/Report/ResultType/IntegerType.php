<?php

namespace App\Service\Report\ResultType;

class IntegerType extends AbstractType
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return number_format((int)$this->getValue(), 0, '.', ' ');
    }
}