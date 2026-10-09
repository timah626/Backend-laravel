<?php





use App\Modules\EvidenceAndScoring\Models\Device;


class DeviceRepository {

public function findDeviceToken (string $deviceToken)  :?Device { 

return Device  :: where('token_hash',$deviceToken)
-> first ();
}




}