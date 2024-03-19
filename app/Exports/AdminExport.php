<?php
namespace App\Exports;

use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    public function __construct(string $searchValue = null) {
        $this->searchValue = $searchValue;
    }

    public function query()
    {
        return Admin::query()
            ->select('id','name','email')
            ->when($this->searchValue, function($query, $searchValue) {
                $query->where('name', 'LIKE', '%'.$searchValue.'%')
                        ->orWhere('email', 'LIKE', '%'.$searchValue.'%');
                        
            })->orderBy('id','asc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            
        ];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->name ?? '-',
            $employee->email,
            
        ];
    }
}
