<?php

namespace App\Service\Report\ResultType;

class StringType extends AbstractType
{
    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getValue();
    }
}