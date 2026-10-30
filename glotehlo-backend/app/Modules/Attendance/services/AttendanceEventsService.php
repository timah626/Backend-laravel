<?php



namespace App\Modules\Attendance\services;

use App\Modules\Attendance\Repository\AttendanceEventRepository;
use App\Modules\Organisation\Repository\OrganisationRepository;
use App\Modules\Attendance\Repository\AttendanceDaysRepository;
use App\Modules\Onboarding\Services\PageResponse;
use App\Modules\EvidenceAndScoring\Services\EvidenceAndScoringService;
use Carbon\Carbon;
use App\Modules\Users\Services\UserPermission;


use Illuminate\Support\Facades\DB;

use App\Exceptions\AppException;





class AttendanceEventsService {

public function __construct (

  private AttendanceEventRepository $attendanceEventRepository,
  private OrganisationRepository $organisationRepository,
  private AttendanceDaysRepository $attendanceDayRepository,
  private EvidenceAndScoringService $evidenceAndScoringService,
  private UserPermission $userPermission,


) {}






    public function clock($user, $ip, array $data,)
    {

        if (! $this->userPermission->can($user, 'clock_in'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $flags = [];


        $existingEvent = $this->attendanceEventRepository ->findByIdempotencyKey($data['idempotency_key']);

        if ($existingEvent)
         {
            return ['event' => $existingEvent, 'day' => null, 'already_recorded' => true];


         }


        $department = $this->organisationRepository->getDepartmentsBySlug($data['qr_slug']);

        if (! $department)
        {
            throw new AppException
                (
                    'NOT_FOUND',
                    'Not found dear',
                     404
                );
        }




        if (! $user || ! $user->active)
        {
            throw new AppException
            (
                'INVALID_USER',
                'Not allowed',
                403
            );
        }


        if ($user->department_id !== $department->id)
        {
            $flags[] = 'wrong_department';
        }


        $site = $this->organisationRepository->findSite($department->site_id);



        if (! $site)
         {
            throw new AppException
            (
                'NOT_FOUND',
                'Not found dear',
                404
            );


         }



        $localDate = now()->setTimezone($site->timezone)->toDateString();

      // chelsie . just waka make dull dull mistake them...... saved together or not at all
        return DB::transaction(function () use ($ip, $data, $user, $department, $site, $flags, $localDate)
        {
         $day = $this->attendanceDayRepository ->findAttendanceOfUserByDay($user->id, $localDate);


        if ($data['kind'] === 'in' && $day)
        {

            throw new AppException
            (
                'ALREADY_CLOCKED_IN',
                'clocked in already dearest',
                409
            );
        }


        if ($data['kind'] === 'out' && !$day)
        {
            $flags[] = 'no_clock_in';

            throw new AppException
            (
                'NOT_CLOCKED_IN',
                'where are you clockcing out to without clocking in dearest',
                409
            );

        }

        $score = $this->evidenceAndScoringService->score(  $user, $ip, $site->id, $data['device_token'] ?? null);

        $event = $this->attendanceEventRepository->createEvent([
            'user_id'            => $user->id,
            'site_id'            => $department->site_id,
            'department_id'      => $department->id,
            'kind'               => $data['kind'],
            'source_ip'          => $ip,
            'state'              => $score['state'],
            'matched_network_id' => $score['matchedNetworkId'],
            'flags'              => array_merge($flags, $score['flags']),
            'idempotency_key'    => $data['idempotency_key'],
            'device_id'          => $score['deviceId'],
        ]);

        $localTime = $event->server_time->copy()->setTimezone($site->timezone);


        if ($data['kind'] === 'in')
         {
                $day = $this->attendanceDayRepository->createAttendanceDay([
                'user_id'       => $user->id,
                'site_id'       => $department->site_id,
                'department_id' => $user->department_id,
                'date'          => $localDate,
                'first_in_at'   => $event->server_time,
                'late'          => $localTime->format('H:i:s') > $site->start_time,
                'state'         => $event->state,
            ]);
        }



        if ($data['kind'] === 'out' && $day)
        {

            $day = $this->attendanceDayRepository->setLastOut($day, $event->server_time);
        }

        return ['event' => $event, 'day' => $day, 'already_recorded' => false,
        'device_token' => $score['newToken']];
        });
    }





    public function getMyAttendanceToday ($user)
    {
         if (! $this->userPermission->can($user, 'view_own_history'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $userId = $user -> id;

        $departmentId =  $user -> department_id;

        $site = $this -> organisationRepository-> findSiteByDepartmentId($departmentId);



        if (!$site)
        {

            throw new AppException
            (
                'NOT_FOUND',
                'Not found dear',
                404
            );

        }

        $localDate = now()->setTimezone($site->timezone)->toDateString();


        $attendanceToday = $this -> attendanceDayRepository -> findAttendanceOfUserByDay($userId, $localDate);


        return $attendanceToday;

    }



    public function getAllMyAttendance ($user) {

        $userId = $user -> id;

        if (! $this->userPermission->can($user, 'view_own_history'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );


         }


         $allMyAttendance = $this -> attendanceDayRepository ->  allMyAttendance ($userId);


         return PageResponse::from($allMyAttendance);

    }


    public function getMyAttendanceSummary ($user) {

        $userId = $user -> id;

        if (! $this->userPermission->can($user, 'view_own_history'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );


        }


        $departmentId =  $user -> department_id;

        $site = $this -> organisationRepository-> findSiteByDepartmentId($departmentId);

        $localDate = now()->setTimezone($site->timezone)->toDateString();

        $attendanceToday = $this->attendanceDayRepository->findAttendanceOfUserByDay($userId, $localDate);

        $weekStart = now()->setTimezone($site->timezone)->startOfWeek(Carbon::MONDAY)->toDateString();

        $countOfAttendanceDays = $this -> attendanceDayRepository -> countUserDaysBetween($userId,  $weekStart, $localDate);

        $monthStart = now()->setTimezone($site->timezone)->startOfMonth()->toDateString();

        $monthEnd = now()->setTimezone($site->timezone)->endOfMonth()->toDateString();

        $lateThisMonth = $this->attendanceDayRepository->countLateDaysBetween($userId, $monthStart,$monthEnd);



        return
        [
            'today' => $attendanceToday,
            'week_present' => $countOfAttendanceDays,
            'week_days' => 5,
            'late_this_month' => $lateThisMonth,
        ];



    }
















}









