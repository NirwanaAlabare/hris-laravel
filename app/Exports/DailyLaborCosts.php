<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class DailyLaborCosts implements FromView,  WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($query,$first_date,$last_date)
    {
        $this->query=$query;
        $this->first_date=$first_date;
        $this->last_date=$last_date;
    }
    public function view(): View
    {
        return view('hris.Laporan.daily_labor_cost_template',[
            'query'=>$this->query,
            'first_date'=>$this->first_date,
            'last_date'=>$this->last_date
        ]);
    }
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_DATE_TIME3,
            'M' => NumberFormat::FORMAT_DATE_TIME3,
            'N' => NumberFormat::FORMAT_DATE_TIME3,
            'AO' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'AQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BO' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BR' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BS' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BT' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BU' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BV' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BW' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BX' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BY' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'BZ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CA' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CB' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CC' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CD' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CE' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CF' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CG' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CH' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CI' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CJ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CK' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CL' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CM' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CN' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CO' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CP' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CQ' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CR' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CS' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CT' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CU' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CV' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CW' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
            'CX' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED3,
        ];
    }
}
