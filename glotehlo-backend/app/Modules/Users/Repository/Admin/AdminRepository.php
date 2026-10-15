<?php

namespace App\Modules\Users\Repository\Admin;

use App\Modules\Users\Models\User;

use Illuminate\Database\Eloquent\Collection;


class AdminRepository {



    public function findByEmail (string $email)  :?User
    { //chelsie na wow o. gotta return a user object

        return User :: where('email', $email)-> first ();
    }



    public function createUser ( array $data) :?User
    {
        return User::create ($data);
    }




    public function findInternsByDepartment(string $departmentId): Collection
    {
         return User::where('department_id', $departmentId)
            ->where('role', 'intern')
            ->where('active', true)
            ->orderBy('name')
            ->get();
    }


    public function DeleteUserByEmail (string $email)
    {
        return User::where('email', strtolower($email))->delete();

    }



}




