<?php


namespace App\Modules\Attendance\Repository;

use App\Modules\Attendance\Models\AttendanceDay;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Collection;





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



    public function allMyAttendance ($userId,  int $perPage = 10): LengthAwarePaginator
    {
      return AttendanceDay ::where ('user_id', $userId)
                            ->orderBy ('date', 'desc')
                            ->paginate($perPage);

    }

    public function countUserDaysBetween(string $userId,  string $startDate, string $endDate): int

    {
        return AttendanceDay::where('user_id', $userId)
        ->whereBetween('date', [$startDate, $endDate])
        ->count();
    }


    public function countLateDaysBetween( string $userId, string $startDate, string $endDate): int
    {
        return AttendanceDay::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('late', true)
            ->count();
   }


   public function getDepartmentDaysByDate ( string $departmentId, string $localDate) :Collection
   {

        return AttendanceDay ::  where('department_id', $departmentId)
                               ->where('date', $localDate)
                               ->get();
   }


   public function getDepartmentHistory(string $departmentId, string $from, string $to, ?string $internId,int $perPage = 10): LengthAwarePaginator
   {


        $query = AttendanceDay::with('intern')
                              ->with('department')
                              ->where('department_id', $departmentId)
                              ->whereBetween('date', [$from, $to]);

        if ($internId!== null)
        {
             $query->where('user_id', $internId);
        }

        return $query->orderBy('date', 'desc')
             ->paginate($perPage);
    }


    public function findDayById (string $dayId) :AttendanceDay
    {
       return AttendanceDay :: where ('id', $dayId)
                            ->first();
    }


    public function MarkAttendanceAsConfirmed (AttendanceDay $day, string $userId) :AttendanceDay
    {
        $day -> state = 'confirmed';
        $day -> reviewed_at = now();
        $day->reviewed_by = $userId;
        $day -> save();

        return $day;

    }


     public function MarkAttendanceAsRejected (AttendanceDay $day, string $userId) :AttendanceDay
    {
        $day -> state = 'rejected';
        $day -> reviewed_at = now();
        $day->reviewed_by = $userId;
        $day -> save();

        return $day;

    }




















}
