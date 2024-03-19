<?php
namespace App\Models;

class AttUserInfo extends \Eloquent
{

    protected $connection = 'sqlsrv2';

    protected $table = 'USERINFO';

    // Don't forget to fill this array
    protected $fillable = [
        'USERID',
        'Badgenumber',
        'SSN',
        'Name'
    ];
    protected $guarded = [];

    protected $hidden = [];

    protected $appends = [];

    protected $casts = [
        'USERID',
        'Badgenumber',
        'SSN',
        'Name'
    ];

    public $incrementing = false;
    // public $primaryKey = null;
    public $primaryKey = [
        'USERID'
    ];

}
