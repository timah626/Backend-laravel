<?php

namespace App\Modules\Organisation\Services;

use App\Modules\Organisation\Repository\OrganisationRepository;


use Illuminate\Support\Str;

use App\Modules\Users\Services\UserPermission;

class OrganisationService {

 public function __construct (private OrganisationRepository $organisationRepository, 
 private UserPermission  $userPermission,
 ) {}



 public function createSites (array $data, $user ) {

    if (! $this->userPermission->can($user, 'manage_sites')) {
    abort(403, 'Forbidden');
    ;}



    $createdSite = $this->organisationRepository->createSite($data);





    return $createdSite;
}








 public function createDepartment($user, array $validatedData, string $siteId)
{
    if (! $this->userPermission->can($user, 'manage_departments')) {
        abort(403, 'Forbidden');
    }

    $validatedData['site_id'] = $siteId;

    $validatedData['qr_slug'] = Str::lower(Str::random(8));

    return $this->organisationRepository->createDepartment($validatedData);
}



public function listDepartments($user,  string $siteId)
{
    if (! $this->userPermission->can($user, 'manage_departments')) {
         abort(403, 'Forbidden');
    }


    return $this->organisationRepository->findDepartmentbySite($siteId);
}








 
 }









  
  