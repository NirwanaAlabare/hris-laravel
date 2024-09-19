<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class dailyLaborCost extends DefaultValueBinder implements  WithCustomValueBinder, FromView, WithColumnFormatting, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($daily_labor)
    {
        $this->daily_labor=$daily_labor;
    }
    public function view(): View
    {
        return view('hris.Laporan.daily_labor_excel',[
            'daily_labor'=>$this->daily_labor,
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'B' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'L' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'P' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'U' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'V' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'W' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'X' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'Y' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'AD' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'B') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'C') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'D') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'E') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'F') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'G') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'H') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'I') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'J') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'K') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'L') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'M') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'N') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'O') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'P') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'Q') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'R') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'S') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'T') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'U') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'V') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'W') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'X') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'Y') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'AA') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'AB') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'AC') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        if ($cell->getColumn() == 'AD') {
            if (is_numeric($value)) {
                $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
    
                return true;
            }
        }
        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
