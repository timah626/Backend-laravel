<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AttendanceEvent extends Model
{
    use HasUlids;

    protected $table = 'attendance_events';
    protected $guarded = [];
}
