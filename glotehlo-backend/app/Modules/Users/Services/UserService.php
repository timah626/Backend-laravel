<?php

namespace App\Modules\Users\Services;

use App\Modules\Organisation\Repository\OrganisationRepository;


use App\Modules\Users\Services\UserPermisionService;



use App\Modules\Users\Repository\UserRepository;


class UserService {
   public function __construct (


   private UserRepository $userRepository,

    private OrganisationRepository $organisationRepository,

    private UserPermission $userPermission
   ){
   }

     
   public function getInternsByDepartment($user,string $departmentId ) {

    
   if (! $this->userPermission->can($user, 'list_interns')) {
    throw new \Exception('FORBIDDEN');
}
   

    $department = $this->organisationRepository->findDepartment($departmentId);

    if (!$department) {
        throw new \Exception('Department not found');
    }


    if ($user->role === 'intern_head'
    && $user->department_id !== $department->id) {
    throw new \Exception('FORBIDDEN');
}

    return $this->userRepository->findInternsByDepartment($departmentId);

   }



}





