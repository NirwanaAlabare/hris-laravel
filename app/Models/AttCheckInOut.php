<?php
namespace App\Models;

class AttCheckInOut extends \Eloquent
{

    protected $connection = 'sqlsrv2';

    protected $table = 'CHECKINOUT';

    // Don't forget to fill this array
    protected $fillable = [
        'USERID',
        'CHECKTIME',
        'CHECKTYPE',
        'VERIFYCODE',
        'SENSORID',
        'Memoinfo',
        'WorkCode',
        'sn',
        'UserExtFmt'
    ];
    protected $guarded = [];

    protected $hidden = [];

    protected $appends = [];

    protected $casts = [
        'USERID',
        'CHECKTIME',
        'CHECKTYPE',
        'VERIFYCODE',
        'SENSORID',
        'Memoinfo',
        'WorkCode',
        'sn',
        'UserExtFmt'
    ];

    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = [
        'USERID', 'CHECKTIME'
    ];

}
