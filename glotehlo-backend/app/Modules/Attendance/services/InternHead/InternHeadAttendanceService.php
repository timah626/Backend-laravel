<?php



namespace App\Modules\Attendance\services\InternHead;

use App\Modules\Users\Services\UserPermission;

use App\Modules\Attendance\Repository\AttendanceDaysRepository;

use App\Modules\Users\Repository\Admin\AdminRepository;

use  App\Modules\Onboarding\Services\PageResponse;




use App\Exceptions\AppException;



class InternHeadAttendanceService {

     public function __construct (

        private AttendanceDaysRepository $attendanceDayRepository,

        private UserPermission $userPermission,

        private AdminRepository $adminRepository



     ){}


    public function getAttendanceperDepartmentToday ($user)
    {

        if (! $this->userPermission->can($user, 'view_department_today'))
        {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }

        $departmentId = $user -> department_id;

        $localDate = now()->setTimezone('Africa/Douala') ->toDateString(); //lavareltimezoneIdentifier for cameroon

        $interns = $this->adminRepository->findInternsByDepartment($departmentId);

        $days = $this->attendanceDayRepository->getDepartmentDaysByDate( $departmentId, $localDate);


        $rows = [];

            foreach ($interns as $intern)
            {
                $day = null;

                foreach ($days as $attendanceDay)
                {

                    if ($attendanceDay->user_id === $intern->id)
                    {
                         $day = $attendanceDay;
                         break;
                    }

                }

                 $rows[] =
                 [

                    'intern' => [
                         'id' =>$day ->intern->id,
                         'name' =>$day->intern->name,
                         ],
                     'first_in_at' => $day->first_in_at,
                     'last_out_at' => $day->last_out_at,
                     'state' => $day->state,

                   // 'day' => $day,
                 ];
            }


        $present = 0;
        $notIn = 0;
        $late = 0;
        $toReview = 0;

                foreach ($rows as $row)
                {
                    if ($row['day'] !== null)
                    {
                        $present++;
                    }
                    else
                    {
                     $notIn++;
                    }
                }

                foreach ($days as $day)
                {

                    if ($day->late === true)
                    {
                        $late++;
                    }

                    if ($day->state === 'unverified' || $day->state === 'pending')
                    {
                        $toReview++;

                    }


                }

        $summary =
        [
             'present' => $present,
             'late' => $late,
             'not_in' => $notIn,
             'to_review' => $toReview,
        ];



        return [
           'summary' => $summary,
           'rows' => $rows,
              ];

    }


    public function getDepartmentHistory ($user, array $validatedData)

    {

       if (! $this->userPermission->can($user, 'view_department_today'))
        {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

        }

        $departmentId = $user->department_id;

        $localDate = now()->setTimezone('Africa/Douala') ->toDateString();


        $to = $validatedData['to'] ?? $localDate;

        $from = $validatedData['from'] ?? now()->setTimezone('Africa/Douala') ->subDays(29) ->toDateString();


        if (isset($validatedData['intern_id']))
        {

            $intern = $this->adminRepository->findById($validatedData['intern_id']);


            if ( !$intern || $intern->department_id !== $departmentId || $intern->role !== 'intern')
            {

                throw new AppException
                (
                'FORBIDDEN',
                'This intern does not belong to your department.',
                 403
                );
            }

        }


        $page = $this->attendanceDayRepository->getDepartmentHistory($departmentId, $from, $to, $validatedData['intern_id'] ?? null, $validatedData['per_page'] ?? 10);


        return PageResponse::from($page);


    }


    public function confirmAttendance ($user, string $dayId)
    {
        $day = $this -> attendanceDayRepository -> findDayById($dayId);
        $userId = $user -> id;

        if (!$day)
        {
            throw new AppException
            (
            'NOT_FOUND',
            'Attendance record not found',
             404
            );
        }



        if (! $this->userPermission->can($user, 'confirm_record',  $day))
        {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

        }

        if ($day->state !== 'unverified' && $day->state !== 'pending')
        {
            throw new AppException
             (
                'ALREADY_DECIDED',
                'this Attendance has been checked already',
                 409
             );


        }

        















    }










}

