<?php


namespace App\Modules\EvidenceAndScoring\Services;

use App\Modules\OfficeNetworks\Services\OfficeNetworkService;

use App\Modules\EvidenceAndScoring\Repository\DeviceRepository;




class EvidenceAndScoringService {

    public function __construct(
         private OfficeNetworkService $officeNetworkService,

        private DeviceRepository $deviceRepository
    ){}



    public function score($user, $ip, string $siteId, ?string $deviceToken)
    {
        $network = $this->officeNetworkService->matchIp($ip, $siteId);
        $strong  = $network && $network->strength === 'strong';

        $device = $this->checkDevices($user, $deviceToken);

        if ($strong)
        {
            $state = 'clean';            // known or new device: strong presence wins
        }
        elseif ($device['known'])
        {
            $state = 'unverified';       // known device, weak or no presence
        }
        else
        {
        $state = 'pending';          // new device and no presence
        }

        return
         [
            'state'            => $state,
            'matchedNetworkId' => $network?->id,
            'deviceId'         => $device['deviceId'],
            'flags'            => $device['flags'],
            'newToken'         => $device['newToken'],
         ];
}



    public function checkDevices($user, ?string $tokenFromRequest)
    {

         $flags = [];


         if ($tokenFromRequest)
         {

            $hashToken = hash('sha256', $tokenFromRequest);

            $device = $this->deviceRepository ->findDeviceTokenByHash($hashToken);


            if ($device)
            {

                if ( $device->user_id ===  $user->id)

                {
                    $this->deviceRepository->updateLastSeenAt($device);

                    return
                    [
                        'known' => true,
                        'deviceId' => $device->id,
                        'flags' => [],
                        'newToken' => null,
                    ];
                }

            //yup if it belongs to another user
                $flags[] = 'shared_device';
            }
         }

        $existingDevice = $this->deviceRepository ->findUserDevice($user->id);

        if ($existingDevice === null)
        {
           $first = true;
        }
        else
        {
          $first = false;
        }


        $plain = bin2hex(random_bytes(32));

        $hash = hash('sha256', $plain);


        $device = $this->deviceRepository->createDevice([
             'user_id' => $user->id,
             'token_hash' => $hash,
             'label' => 'Unknown device',
             'first_seen_at' => now(),
             'last_seen_at' => now(),
        ]);


        $known = $first && empty($flags);

        if (!$known)
        {
             $flags[] = 'new_device';
        }


        return
         [
             'known' => $known,
             'deviceId' => $device->id,
             'flags' => $flags,
             'newToken' => $plain,
         ];

    }

}



