<?php



namespace App\Modules\Attendance\services;


use Illuminate\Support\Facades\App;






class AttendanceMessageService {

 public function messageCode (string $kind, string $state,  $alreadyRecorded ) {

   if (!$alreadyRecorded) {
    return "ALREADY_CLOCKED_IN";
   };


   if ($state === 'pending') {
    return 'PENDING_REVIEW';
   };


   if ($state === 'unverified'){
    return 'PRESENCE_NOT_VERIFIED';
   };


   if ($kind === 'in')  {
    return 'CLOCKED_IN';
   }


   else{

   return 'CLOCKED_OUT';
   }

 }




}




