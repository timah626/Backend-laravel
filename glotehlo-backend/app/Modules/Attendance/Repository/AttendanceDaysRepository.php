<?php


namespace App\Modules\Attendance\Repository;
use App\Modules\Users\Models\User;
use App\Modules\Attendance\Models\AttendanceDay;



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










}
