<?php


namespace App\Modules\EvidenceAndScoring\services;

use App\Modules\OfficeNetworks\services\OfficeNetworkService;


use App\Modules\EvidenceAndScoring\Repository\DeviceRepository;







class EvidenceAndScoringService {

public function __construct(
    private OfficeNetworkService $officeNetworkService,

   // private DeviceRepository $deviceRepository
){}



public function score($ip, string $siteId) {

 $flags = [];
 $state = 'unverified';

 $network = $this -> officeNetworkService -> matchIp ($ip, $siteId);


 if ($network && $network->strength === 'strong'){
    $state =  'clean';
 } else {

     if (!$network) {
        $state = 'unverified';
     }
 }




  return [
      'state'  => $state,
      'matchedNetworkId' => $network ? $network->id : null,
       'flags' =>
        $flags,
    ];
}





/*public function checkDevice ($user , string $deviceToken) {

$flags = [];


$existingDeviceToken =  $this -> deviceRepository -> findDeviceToken ($deviceToken);



if (!$existingDeviceToken) {


return ['known' => false, $deviceToken-> token_hash => null,  $flags ['new_device']];


}









}
    */

}



