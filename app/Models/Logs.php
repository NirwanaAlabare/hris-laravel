<?php
namespace App\Models;

class Logs extends \Eloquent
{

    protected $table = 'logs';

    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['id'];

}
