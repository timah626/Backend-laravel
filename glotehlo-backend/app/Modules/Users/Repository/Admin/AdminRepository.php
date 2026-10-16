<?php

namespace App\Modules\Users\Repository\Admin;

use App\Modules\Users\Models\User;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Collection;
use LengthException;


use Illuminate\Support\Facades\DB;

class AdminRepository {


    public function findByEmail (string $email)  :?User
    { //chelsie na wow o. gotta return a user object

        return User :: where('email', $email)-> first ();
    }



    public function findById (string $userId)  :?User
    {

        return User :: where('id', $userId)-> first ();
    }


    public function getUsersFirstName (string $userId) :? User

    {

        return User :: where ('id', $userId)
                       ->select ('first_name')
                       ->get();

    }




    public function setTempPassword($user, string $plain)
        {
            $user->password = $plain;
            $user->temp_password_active = true;
            $user->save();

            return $user;
        }

    public function deleteSessions(string $userId)
        {

            DB::table('sessions')->where('user_id', $userId)->delete();

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



    public function getSearchedUser (string $search, int $perPage = 10) : LengthAwarePaginator
    {

        return User :: where ('name', 'ilike' , "%{$search}%")
                      ->with('suggestedDepartment:id,name')
                      ->select('id', 'name',  'email', 'school', 'department_id')
                      ->orWhere('email', 'ilike', "%{$search}%")
                      ->paginate($perPage);
    }





    //test function ooo
    public function DeleteUserByEmail (string $email)
    {
        return User::where('email', strtolower($email))->delete();

    }



}




