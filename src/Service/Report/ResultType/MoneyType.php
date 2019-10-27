<?php

namespace App\Service\Report\ResultType;

class MoneyType extends AbstractType
{
    /**
     * @var string
     */
    private $format;

    public function __construct(string $format = '%s р.')
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf($this->format, number_format($this->getValue(), 2, '.', ' '));
    }
}