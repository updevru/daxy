<?php

namespace App\Service\Storage;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;

class BigQuery implements StorageInterface
{
    /**
     * @var \Google\Cloud\BigQuery\Dataset
     */
    private $dataSet;

    protected $isFirst = true;

    /**
     * BigQuery constructor.
     * @param string $projectId
     * @param string $datasetId
     * @param string $keyFile
     */
    public function __construct(string $projectId, string $datasetId, $keyFile)
    {
        $bigQuery = new BigQueryClient([
            'projectId' => $projectId,
            'keyFile' => $keyFile
        ]);
        $this->dataSet = $bigQuery->dataset($datasetId);
    }

    public function upload($source, $tableName)
    {
        $table = $this->dataSet->table($tableName);

        if (is_resource($source)) {
            $loadConfig = $table->load($source);
        } else {
            $loadConfig = $table->load(fopen($source, 'r'));
        }

        if ($this->isFirst) {
            $loadConfig->writeDisposition('WRITE_TRUNCATE');
            $this->isFirst = false;
        } else {
            $loadConfig->writeDisposition('WRITE_APPEND');
            $loadConfig->schemaUpdateOptions(['ALLOW_FIELD_ADDITION', 'ALLOW_FIELD_RELAXATION']);
        }

        $loadConfig->sourceFormat('NEWLINE_DELIMITED_JSON');
        $loadConfig->autodetect(true);

        $job = $table->runJob($loadConfig);

        $backoff = new ExponentialBackoff(10);
        $backoff->execute(function () use ($job) {
            $job->reload();
            if (!$job->isComplete()) {
                throw new \Exception('Job has not yet completed', 500);
            }
        });

        if (isset($job->info()['status']['errorResult'])) {
            $error = $job->info()['status']['errorResult']['message'];
            throw new \Exception(sprintf('Error running job: %s' . PHP_EOL, $error));
        }

        return true;
    }
}