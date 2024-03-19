<?php
namespace App\Models;

class BgProcess extends \Eloquent
{
    protected $table = 'bgprocess';

    // Don't forget to fill this array
    protected $fillable = [
        'uuid',
        'nama_process',
        'process_start',
        'process_end',
        'process_status',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $guarded = ['uuid'];

    protected $hidden = [];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'uuid',
        'nama_process',
        'process_start',
        'process_end',
        'process_status',
        'operator',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [];
    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = ['uuid'];

}
