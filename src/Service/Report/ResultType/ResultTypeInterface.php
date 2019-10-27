<?php

namespace App\Service\Report\ResultType;

interface ResultTypeInterface
{
    /**
     * @param string $name
     * @param array $row
     */
    public function setData(string $name, array $row);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return string
     */
    public function __toString(): string;
}