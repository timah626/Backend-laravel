<?php

namespace App\Modules\OfficeNetworks\Repository;
use App\Modules\OfficeNetworks\Models\OfficeNetwork;

use Illuminate\Database\Eloquent\Collection;










class OfficeNetworkRepository {

public function findByNetwork($network, $siteId)
{
    return OfficeNetwork::where('network', $network)
        ->where('siteid', $siteId)
        ->first();
}



public function createNetwork(array $data): OfficeNetwork
{
    return OfficeNetwork::create($data);
}





public function getAllNetworks(string $siteId): Collection
{
   return OfficeNetwork::where('site_id', $siteId)->get();
}




public function findMatchingIp ( $ip,    $siteId,) {

     return OfficeNetwork::where('site_id', $siteId)
     -> where ('status',  'trusted' )
     -> whereRaw('network >>= ?', [$ip])  
     ->first();
     }


     public function LastSeen(OfficeNetwork $network) :     OfficeNetwork {
      $network->last_seen_at = now();
      $network->save();

      return $network;

     }

}





