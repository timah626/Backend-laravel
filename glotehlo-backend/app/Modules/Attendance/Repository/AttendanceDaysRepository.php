<?php


namespace App\Modules\Attendance\Repository;

use App\Modules\Attendance\Models\AttendanceDay;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;





class AttendanceDaysRepository {



 public function findAttendanceOfUserByDay(string $userId, string $date): ?AttendanceDay
{
    return AttendanceDay::where('user_id', $userId)
        ->where('date', $date)
        ->first();
}




    public function createAttendanceDay (array $data) : AttendanceDay
     {


         return AttendanceDay::create ($data);

    }



    public function setLastOut(AttendanceDay $day, string $time): AttendanceDay
      {
          $day->last_out_at = $time;
          $day->save();

          return $day;
      }

      

    public function allMyAttendance ($userId,    int $perPage = 10): LengthAwarePaginator
    {
      return AttendanceDay ::where ('user_id', $userId)
                            ->orderBy ('date', 'desc')
                            ->paginate($perPage);

    }















}
