<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HistoryProsesLabor extends Model
{
    use HasFactory;

    protected $table = 'history_proses_labor';

    protected $fillable = [
        'tanggal_awal',
        'tanggal_akhir',
        'operator',
    ];
}
