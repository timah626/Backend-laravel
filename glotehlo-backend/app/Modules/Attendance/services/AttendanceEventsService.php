<?php



namespace App\Modules\Attendance\services;

use App\Modules\Users\Repository\UserRepository;
use App\Modules\Attendance\Repository\AttendanceEventRepository;
use App\Modules\Organisation\Repository\OrganisationRepository;
use App\Modules\Attendance\Repository\AttendanceDaysRepository;

use App\Modules\EvidenceAndScoring\services\EvidenceAndScoringService;

use App\Modules\Attendance\services\AttendanceMessageService;


use Illuminate\Support\Facades\DB;





class AttendanceEventsService {

public function __construct (
  private UserRepository $userRepository,
  private AttendanceEventRepository $attendanceEventRepository,
  private OrganisationRepository $organisationRepository,
  private AttendanceDaysRepository $attendanceDayRepository,
  private EvidenceAndScoringService $evidenceAndScoringService,
  private AttendanceMessageService $attendanceMessageService,

) {}






public function clock($ip, array $data)
{
    $flags = [];

    //
    $existingEvent = $this->attendanceEventRepository
        ->findByIdempotencyKey($data['idempotencyKey']);

    if ($existingEvent) {
        return ['event' => $existingEvent, 'day' => null, 'already_recorded' => true];

        
    }


    $department = $this->organisationRepository->findDepartmentBySlug($data['slug']);

    if (! $department) {
        throw new \Exception('NOT_FOUND');
    }


    $user = $this->userRepository->findByEmail(strtolower($data['email']));

    if (! $user || ! $user->active) {
        throw new \Exception('INVALID_USER');
    }


    if ($user->department_id !== $department->id) {
        $flags[] = 'wrong_department';
    }


    $site = $this->organisationRepository->findSite($department->site_id);



    if (! $site) {
    throw new \Exception('NOT_FOUND');
     }



    $localDate = now()->setTimezone($site->timezone)->toDateString();

    // chelsie . just waka make dull dull mistake them...... saved together or not at all
    return DB::transaction(function () use ($ip, $data, $user, $department, $site, $flags, $localDate) {

        $day = $this->attendanceDayRepository
            ->findAttendanceOfUserByDay($user->id, $localDate);

        if ($data['kind'] === 'out' && !$day) {
            $flags[] = 'no_clock_in';
        }



        $score = $this ->evidenceAndScoringService-> score ($ip,  $site);

        $event = $this->attendanceEventRepository->createEvent([
            'user_id'         => $user->id,
            'site_id'         => $department->site_id,
            'department_id'   => $department->id,
            'kind'            => $data['kind'],
            'source_ip'       => $ip,
            'state'           => $score ['state'],
            'matched_network_id' => $score['matchedNetworkId'],

            'flags'              => array_merge($flags, $score['flags']),
            'idempotency_key' => $data['idempotencyKey'],
        ]);

        $localTime = $event->server_time->copy()->setTimezone($site->timezone);


        if ($data['kind'] === 'in' && $day ) {

          return [ 'day' => $day, 'already_recorded' => true];
        }

        if ($data['kind'] === 'in' && ! $day) {
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




        if ($data['kind'] === 'out' && $day) {
            $day = $this->attendanceDayRepository->setLastOut($day, $event->server_time);
            return ['day' => $day, 'already_recorded' => true];
        }

        return ['event' => $event, 'day' => $day, 'already_recorded' => false];
    });
}












}









