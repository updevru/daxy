<?php

namespace App\Service\Storage;

interface StorageInterface
{
    /**
     * @param array $source
     * @param string $tableName
     */
    public function upload($source, $tableName);
}