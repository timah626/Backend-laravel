<?php
namespace App\Modules\OfficeNetworks\Services;


use App\Modules\Organisation\Repository\OrganisationRepository;

use App\Modules\OfficeNetworks\Repository\OfficeNetworkRepository;

use App\Modules\Users\Services\UserPermission;

use App\Exceptions\AppException;




class OfficeNetworkService {

    public function __construct
    (
      private OrganisationRepository $organisationRepository,

      private UserPermission $userPermission,

   private OfficeNetworkRepository $officeNetworkRepository
    )
    {}


    public function addCurrentConnection ($user, string $ip, string $label, string $siteId)

    {


        if (! $this->userPermission->can($user, 'manage_office_networks'))
         {
              throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );
        }




        if (!$this ->organisationRepository ->findSite($siteId))
        {
             throw new AppException
             (
                'NOT_FOUND',
                'Site not found',
                 404
             );
        }




        $isIPv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;  // you know it returns false wen it fails na timah.. God abeg syntax upon syntax


        if ($isIPv6)
         {
             $bytes = inet_pton($ip);
             $networkAddress = inet_ntop(substr($bytes, 0, 8) . str_repeat("\0", 8)) . '/64';
             $strength = 'strong';
         }

        else
         {
             $networkAddress = $ip . '/32';
             $strength = 'weak';
         }

        $existingNetwork = $this->officeNetworkRepository->findByNetwork($siteId, $networkAddress);


        if ($existingNetwork)
        {

        return ['network' => $existingNetwork, 'already_saved' => true];
        }



        $officeNetwork = $this->officeNetworkRepository->createNetwork([

        'site_id'      => $siteId,
        'label'        => $label,
        'network'      => $networkAddress,
        'strength'     => $strength,
        'status'       => 'trusted',
        'source'       => 'setup_walk',
        'last_seen_at' => now(),
        'confirmed_by' => $user->id,
        'confirmed_at' => now(),
        ]);

        return ['network' => $officeNetwork, 'already_saved' => false];



    }





    public function getAllNetworkBySite ($user , string $siteId)

    {


        if (! $this->userPermission->can($user, 'manage_office_networks'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'forbidden dearest',
                 403
             );
         ;}

        $networks =$this->officeNetworkRepository->getAllNetworks($siteId);

        return $networks;




    }



    public function matchIP ( $ip, string $siteId)
    {

         $network = $this -> officeNetworkRepository -> findMatchingIp ($ip, $siteId);

         if (!$network)
            {

             return  null ;
            }


         else

            {

            $this->officeNetworkRepository ->LastSeen($network);

            return $network;

            }
    }









}

