<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class LimitedRowsFilter implements IReadFilter
{
    protected $startRow;
    protected $endRow;

    public function __construct($startRow = 1, $endRow = 200)
    {
        $this->startRow = $startRow;
        $this->endRow = $endRow;
    }

    public function readCell($columnAddress, $row, $worksheetName = ''): bool
    {
        return $row >= $this->startRow && $row <= $this->endRow;
    }
}
