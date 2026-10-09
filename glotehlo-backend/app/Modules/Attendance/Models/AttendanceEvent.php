<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AttendanceEvent extends Model
{
    use HasUlids;


  

    protected $table = 'attendance_events';
    protected $guarded = [];
    public $timestamps = false;






    protected $fillable = [
    'user_id', 'site_id', 'department_id', 'kind', 'source_ip',
    'state', 'flags', 'idempotency_key',
];





protected function casts(): array    //Laravel, when you take this database value and put it into my PHP model, please treat it as this type.
{
    return [
        'server_time' => 'datetime',
    ];
}



}




