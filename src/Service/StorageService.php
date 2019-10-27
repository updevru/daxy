<?php
/**
 * Created by PhpStorm.
 * User: Sergey
 * Date: 04.07.2019
 * Time: 1:00
 */

namespace App\Service;

use App\Service\Storage\BigQuery;
use \Google\Cloud\BigQuery\BigQueryClient;
use \Google\Cloud\Core\ExponentialBackoff;

class StorageService
{
    /**
     * @var resource
     */
    private $resource;

    /**
     * @var BigQuery
     */
    private $bigquery;

    /**
     * @var string
     */
    private $collection;

    /**
     * @var string
     */
    protected $fileName;

    public function __construct(BigQuery $bigquery, $collection)
    {
        $this->bigquery = $bigquery;
        $this->collection = $collection;
    }

    public function store(array $data)
    {
        if (!is_resource($this->resource)) {
            $this->fileName = rtrim(sys_get_temp_dir(), '/') . '/' . $this->collection . '_' . time();
            $this->resource = fopen($this->fileName, 'w+');
        }

        fwrite($this->resource, json_encode($data) . "\n");
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function flush()
    {
        if (!is_resource($this->resource)) {
            return false;
        }

        try {
            $result = $this->bigquery->upload($this->resource, $this->collection);
        } finally {
            if (is_resource($this->resource)) {
                fclose($this->resource);
                $this->resource = null;
            }

            if (file_exists($this->fileName)) {
                unlink($this->fileName);
            }
        }

        return $result;
    }
}