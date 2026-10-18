<?php


namespace App\Modules\EvidenceAndScoring\Repository;


use App\Modules\EvidenceAndScoring\Models\Device;


class DeviceRepository {

    public function findDeviceTokenByHash (string $deviceToken)  :?Device
    {
        return Device  :: where('token_hash',$deviceToken)
                       -> first ();
    }


    public function findUserDevice (string $userId) :? Device
    {

        return Device :: where ('user_id', $userId)
                        ->first();
    }


    public function updateLastSeenAt(Device $device): void
    {
         $device->update([ 'last_seen_at' => now(),]);
    }


    public function createDevice(array $data): Device
    {
        return Device::create($data);
    }


    








}
