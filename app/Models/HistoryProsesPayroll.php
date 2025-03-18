<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HistoryProsesPayroll extends Model
{
    use HasFactory;

    protected $table = 'history_proses_payroll';

    protected $fillable = [
        'last_periode',
        'operator',
    ];
}
