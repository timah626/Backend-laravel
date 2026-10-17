<?php


namespace App\Modules\Auth\Services\Admin;

use App\Modules\Users\Services\UserPermission;

use Illuminate\Support\Str;

use App\Exceptions\AppException;

use App\Modules\Users\Repository\Admin\AdminRepository;

use App\Modules\Users\Services\Admin\AdminUserService;


use Illuminate\Support\Facades\DB;


class AdminAuthService{


    public function __construct (


        private AdminRepository $adminRepository,

        private AdminUserService $adminUserService,

        private UserPermission $userPermission
    ) {}


   public function resetPassword ($user, string $userId)
   {

       $existingUser = $this -> adminRepository ->findById ($userId);


       if (!$existingUser)
       {

         throw new AppException
             (
                'NOT_FOUND',
                'not found buddy',
                 403
             );

       }



       if (! $this->userPermission->can($user, 'reset_password'))
         {
             throw new AppException
             (
                'FORBIDDEN',
                'You are not allowed',
                 403
             );

         }


        $randomString = Str :: random(4);

        $password = $this ->adminUserService-> generateTempPassword($randomString);



        DB::transaction(function () use ($existingUser, $password, $userId)

        {
            $this->adminRepository->setTempPassword($existingUser, $password);

            $this->adminRepository->deleteSessions($userId);
        });

        return ['temporary_password' => $password];



   }








}
