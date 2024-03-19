<?php
namespace App\Models;

class Datacontact extends \Eloquent
{

    protected $table = 'contact';

    // Don't forget to fill this array
    protected $fillable = [];

    protected $guarded = ['employee_id'];

}
