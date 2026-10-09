<?php


namespace App\Modules\Attendance\Repository;

use App\Modules\Attendance\Models\AttendanceEvent;




class AttendanceEventRepository {

 public function findByIdempotencyKey( string  $idempotencyKey)  :? AttendanceEvent
 {

  return AttendanceEvent:: where('idempotency_key', $idempotencyKey)-> first ();

}





public function createEvent(array $data)
{
    // see this one laat ?? we gotta do this one sep...to change "array" to array.. yeah i get this one
    $data['flags'] = '{' . implode(',', $data['flags']) . '}';

    $event = AttendanceEvent::create($data);

    return $event->refresh(); //kinda have to to this na, since its the server creating the time..we gotta load the server time though
}






 }





