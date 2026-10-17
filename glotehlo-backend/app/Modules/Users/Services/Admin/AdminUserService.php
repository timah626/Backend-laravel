<?php

namespace App\Modules\Users\Services\Admin;


use App\Modules\Users\Services\UserPermission;

use App\Modules\Organisation\Repository\OrganisationRepository;

use Illuminate\Support\Str;

use App\Exceptions\AppException;

use  App\Modules\Onboarding\Services\PageResponse;

use Illuminate\Support\Facades\DB;

use App\Modules\Users\Repository\Admin\AdminRepository;


class AdminUserService {
   public function __construct
    (


        private AdminRepository $adminRepository,

        private OrganisationRepository  $organisationRepository,

        private UserPermission $userPermission
   ){}


   public function getInternsByDepartment($user,string $departmentId )

   {


        if (! $this->userPermission->can($user, 'list_interns'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $department = $this->organisationRepository->findDepartment($departmentId);


        if (!$department)
        {
           throw new AppException
             (
                'NOT_FOUND',
                'not found buddy',
                 404
             );
        }


        if ($user->role === 'intern_head' && $user->department_id !== $department->id)
        {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

        }



        return $this->adminRepository->findInternsByDepartment($departmentId);

   }




   public function getInternById($user,  string $userId )

   {


        if (! $this->userPermission->can($user, 'list_interns'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $intern = $this->adminRepository->getInternById($userId);


         return [
        'id'                                         => $intern->id,

        'name'                                       => $intern->name,

        'email'                                      => $intern->email,

        'gender'                                     => $intern->application->gender,

        'school'                                     => $intern->school,

        'level'                                      => $intern->level,

        'major'                                      => $intern->major,
        
        'department'                                 => ['id' => $intern->suggestedDepartment?->id, 'name' => $intern->suggestedDepartment?->name],

        'site'                                       => ['id' => $intern->site?->id, 'name'=>$intern->site->name],


        'start_date'                                 => $intern->start_date,

        'end_date'                                   => $intern->end_date,

        'active'                                     => $intern->active,

        'temp_password_active'                       => $intern->temp_password_active,

        'files'                                      => [
            'cv'                                     => $intern->application?->cv,
            'handwritten_application'                => $intern->application?->handwritten_application,
            'recommendation_letter'                  => $intern->application?->recommendation_letter,
            'attestation_of_attendance'              => $intern->application?->attestation_of_attendance,
            'photo_path'                             => $intern->photo_path,
        ],];

   }



   public function generateTempPassword ($firstname)

   {
        $convertFirstName = Str :: lower ($firstname);

        //$randomLetters = Str :: random(4);

        $finalPassword = $convertFirstName . "@". "glotelho";


        return $finalPassword;


   }




   public function createAccount ( array $draft)

   {
      //just forgive me for doing this man
       $department = $this->organisationRepository->findDepartment($draft['department_id']);


       $tempPassword = $this -> generateTempPassword($draft['first_name']);


       $newUser = $this -> adminRepository -> createUser ([

             'name'          => $draft['first_name'] . ' ' . $draft['last_name'],

             'email'         => $draft['email'],

             'password'      => $tempPassword,

             'major'         => $draft['major'],

             'level'         => $draft['level'],

             'start_date'    => $draft['start_date'],

             'end_date'      => $draft['end_date'],

             'department_id' => $draft['department_id'],

             'site_id'       => $department-> site_id,

             'school'        => $draft['school'],

             'role'          => 'intern',

             'active'        => true,

             'temp_password_active' => true,

             'photo_path' => $draft['photo'],


       ]);



       return

       [

             'user'  => $newUser,

             'tempPassword' => true,

             'temporary_password' => $tempPassword

       ];


   }




   public function searchUser ($user, string $search)
   {

       if (! $this->userPermission->can($user, 'view_users'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $result = $this -> adminRepository -> getSearchedUser($search);

        return PageResponse::from($result);



   }










    public function listInterns($user, array $filters)
     {

        if (! $this->userPermission->can($user, 'list_interns'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $filters['per_page'] = $filters['per_page'] ?? 10;
        $filters['page'] = $filters['page'] ?? 1;

        $result = $this->adminRepository->findInterns($filters);

        return PageResponse::from($result);

    }





   //test function
   public function deleteUser (string $email)
   {

        $existingUser = $this -> adminRepository -> findByEmail($email);


        if (!$existingUser)
        {
            throw new AppException
             (
                'NOT_FOUND',
                'no sourch resource',
                 404
             );
        }

        $this -> adminRepository -> DeleteUserByEmail( $email);


        return ['deleted succesfully'];


   }
}





