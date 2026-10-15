<?php

namespace App\Modules\Onboarding\Services\Admin;

use App\Modules\Onboarding\Repository\ApplicationRepository;

use App\Modules\Users\Repository\Admin\AdminRepository;

use App\Modules\Users\Services\UserPermission;

use App\Modules\Users\Services\Admin\AdminUserService;

use  App\Modules\Onboarding\Services\PageResponse;


use App\Exceptions\AppException;




use Illuminate\Support\Facades\DB;



class  ApplicationService {



    public function __construct
    (
         private ApplicationRepository $applicationRepository,

         private AdminRepository $adminRepository,

         private UserPermission $userPermission,

         private AdminUserService $adminUserService,
    ) {}



    public function getApplicationByStatus ($user , string $status)
    {

        if (!($this->userPermission->can($user, 'review_applications')))
        {

                 throw new AppException(
                    'FORBIDDEN',
                    'You are not allowed',
                    403
        );}

         return PageResponse::from($this->applicationRepository->getByStatus($status));
    }




    public function approveApplication ($user,  string $applicationId, array $validatedData)
     {


         if (!($this->userPermission->can($user, 'review_applications')))
         {

             throw new AppException(
                'FORBIDDEN',
                'You are not allowed',
                 403
         );}


         $existingApplication = $this -> applicationRepository ->  findApplicationById( $applicationId);

         if (!$existingApplication)
         {

             throw new AppException(
                'NOT_FOUND',
                'No sourch resource',
                 404
         );}


         if ( $existingApplication ['status'] === 'approved')
         {

             throw new AppException(
                'ALREADY_DECIDED',
                'Already approved or rejected',
                 409
         );}






        $final =

            [

                'first_name'    => $validatedData['first_name']          ?? $existingApplication->first_name,

                'last_name'     => $validatedData['last_name']           ?? $existingApplication->last_name,

                'email'         => strtolower($validatedData['email']    ?? $existingApplication->email),

                'school'        => $validatedData['school']              ?? $existingApplication->school,

                'level'         => $validatedData['level']               ?? $existingApplication->level,

                'major'         => $validatedData['major']               ?? $existingApplication->major,

                'gender'        =>$validatedData ['gender']              ?? $existingApplication ->gender,

                'department_id' => $validatedData['department_id']       ?? $existingApplication->suggested_department_id,

                'start_date'    => $validatedData['start_date']          ?? $existingApplication->suggested_start_date,

                'end_date'      => $validatedData['end_date']            ?? $existingApplication->suggested_end_date,


                'photo'         => $existingApplication->profile_picture,
            ];



         $existingUser = $this -> adminRepository -> findByEmail($final['email']);


         if ($existingUser)
         {

             throw new AppException(
                'EMAIL_TAKEN',
                'this user already exists',
                 409
         );
         }



        return DB::transaction(function () use ($existingApplication, $final)
        {

             $newAccount = $this -> adminUserService -> createAccount($final);

             $finalApplication = $this -> applicationRepository -> markAsApproved ($existingApplication, $newAccount['user'] -> id);


             return
            [
                'user' => $newAccount['user'],

                'temporary_password' => $newAccount['temporary_password'],

                'already_approved' => false
            ];


        });



     }


    public function rejectApplication ($user , string $applicationId)
     {

         if (!($this->userPermission->can($user, 'review_applications')))
         {

             throw new AppException(
                'FORBIDDEN',
                'You are not allowed',
                 403
         );}


         $existingApplication = $this -> applicationRepository ->  findApplicationById( $applicationId);

         if (!$existingApplication)
         {

             throw new AppException(
                'NOT_FOUND',
                'No sourch resource',
                 404
         );}



         if ( $existingApplication ['status'] === 'approved' )
         {

             throw new AppException(
                'ALREADY_DECIDED',
                'Already approved or rejected',
                 409
         );}


          $this -> applicationRepository ->  markAsRejected( $existingApplication);

          return response()-> json([

             'data' => $existingApplication -> id

          ]);












    }


    //my debugging services
    public function deleteApplication (string $applicationId)
     {
        $existingApplication = $this -> applicationRepository -> deleteApplicationById($applicationId);


        return ['deleted successfully'];
     }





     public function getApplicationById ($user, string $applicationId)
     {

        if (!($this->userPermission->can($user, 'review_applications')))
        {

                 throw new AppException(
                    'FORBIDDEN',
                    'You are not allowed',
                    403
        );}



        $application = $this -> applicationRepository -> findApplicationById($applicationId);

        return $application;

     }



    public function getApplicationsByDepartment ($user , string $departmentId)
     {


        if (!($this->userPermission->can($user, 'review_applications')))
        {

                 throw new AppException(
                    'FORBIDDEN',
                    'You are not allowed',
                    403
        );}



        return PageResponse::from($this->applicationRepository-> getApplicationByDepartmentId($departmentId));

    }


}




