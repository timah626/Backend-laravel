<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AttendanceDay extends Model
{
    use HasUlids;

    protected $table = 'attendance_days';
    protected $guarded = [];
    public $timestamps = false;




    protected function casts(): array
{
    return [
        'date'        => 'date',
        'first_in_at' => 'datetime',
        'last_out_at' => 'datetime',
        'late'        => 'boolean',
    ];
}


}
