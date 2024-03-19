<?php
namespace App\Models;

use App\Models\EmployeeAtribut;

class WorkTimeTable extends \Eloquent
{
    protected $table = 'work_time_table';

    // Don't forget to fill this array
    protected $fillable = [
        'shift_work_id',
        'time_table_id',
        'start_day',
        'time_table_name',
        'start_time',
        'end_time',
        'start_time_break',
        'end_time_break',
        'check_in_time1',
        'check_in_time2',
        'check_out_time1',
        'check_out_time2',
        'plant',
        'start_overtime',
        'end_overtime'
    ];
    protected $guarded = [
        'shift_work_id',
        'time_table_id',
        'start_day'
    ];

    protected $hidden = [
        'shift_work_id',
        'time_table_id',
        'start_day'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'shift_work_id' => 'string',
        'time_table_id' => 'integer',
        'start_day' => 'integer',
        'time_table_name' => 'time',
        'start_time' => 'time',
        'end_time' => 'time',
        'start_time_break' => 'time',
        'end_time_break' => 'time',
        'check_in_time1' => 'time',
        'check_in_time2' => 'time',
        'check_out_time1' => 'time',
        'check_out_time2' => 'time',
        'plant' => 'string',
        'start_overtime' => 'time',
        'end_overtime' => 'time'
    ];

    protected $appends = [];

    /**
     * Get the phone record associated with the user.
     */
    public function EmployeeAtribut()
    {
        return $this->hasOne(EmployeeAtribut::class);
    }
}
