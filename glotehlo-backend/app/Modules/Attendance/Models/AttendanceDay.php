<?php

namespace App\Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

use App\Modules\Users\Models\User;
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
        'reviewed_at' => 'datetime'
    ];
}


    public function intern()
    {
    return $this->belongsTo(User::class, 'user_id');
    }


}
