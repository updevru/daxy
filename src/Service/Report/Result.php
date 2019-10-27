<?php


namespace App\Service\Report;

use App\Service\Report\ResultType\CallbackType;
use App\Service\Report\ResultType\StringType;
use Doctrine\Common\Collections\ArrayCollection;

class Result
{
    /**
     * @var ArrayCollection
     */
    private $columns;

    private $columnsVisible = [];

    /**
     * @var ResultRow[]
     */
    private $rows = [];

    public function __construct()
    {
        $this->columns = new ArrayCollection();
    }

    public function addColumn(string $name, $type, $title = null)
    {
        $this->columns->set(
            $name,
            [
                'name'  => $name,
                'title' => $title ?? str_replace('_', ' ', ucfirst($name)),
                'type'  => $type,
            ]
        );
    }

    /**
     * @return ArrayCollection
     */
    public function getColumns(): ArrayCollection
    {
        if ($this->columnsVisible) {
            $result = [];
            foreach ($this->columnsVisible as $name) {
                $result[$name] = $this->columns->get($name);
            }

            return new ArrayCollection($result);
        }

        return $this->columns;
    }

    public function setVisibleColumns(array $columns)
    {
        $this->columnsVisible = $columns;
    }

    /**
     * @param array $rows
     */
    public function setRows(array $rows)
    {
        foreach ($rows as $row) {
            $this->addRow($row);
        }
    }

    public function addRow(array $row)
    {
        $this->rows[] = new ResultRow($this->columns, $row);
    }

    /**
     * @return ResultRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @return ResultRow
     * @throws \Exception
     */
    public function getTotalRow()
    {
        $result = [];
        $columns = $this->getColumns();
        foreach ($this->getRows() as $row) {
            foreach ($columns as $column) {
                $type = $row->get($column['name']);
                if ($type instanceof StringType) {
                    $result[$column['name']] = '';
                } else {
                    if ($type instanceof CallbackType) {
                        continue;
                    } else {
                        $result[$column['name']] = (isset($result[$column['name']])) ? $result[$column['name']]
                            + $type->getValue() : $type->getValue();
                    }
                }
            }
        }

        return new ResultRow($this->columns, $result);
    }
}