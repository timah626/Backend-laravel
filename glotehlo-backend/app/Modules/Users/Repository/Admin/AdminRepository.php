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






    public function setTempPassword($user, string $plain)
        {
            $user->password = $plain;
            $user->temp_password_active = true;
            $user->save();

            return $user;
        }





    public function setNewPassword($user, string $plain)
        {
            $user->password = $plain;
            $user->temp_password_active = false;
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

   // this is a cute and useless function of mine.
     public function getAllInterns(): Collection

     {
         return User::where('role', 'intern', 'intern_head')
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





    public function getInternById(string $userId)
    {

          return User::with(['suggestedDepartment:id,name', 'site:id,name', 'application'])
            ->where('id', $userId)
            ->whereIn('role', ['intern', 'intern_head'])
            ->where('active', true)
            ->first();
    }



    public function findInterns(array $filters): LengthAwarePaginator


   {
        return User::whereIn('role', ['intern', 'intern_head'])
          ->with([
            'suggestedDepartment:id,name',
            'site:id,name',
            ])

          ->when($filters['site_id'] ?? null, function ($query, $siteId) {
            $query->where('site_id', $siteId);
            })

          ->when($filters['department_id'] ?? null, function ($query, $departmentId) {
            $query->where('department_id', $departmentId);

           })

         ->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                      ->orWhere('email', 'ilike', "%{$search}%");
            });

           })


        ->select('id', 'name', 'email', 'role', 'department_id', 'site_id', 'active' ,'start_date', 'end_date', 'school', 'major'  , 'level')
        ->orderBy('name')
        ->paginate(
            $filters['per_page'] ?? 10,
            ['*'],
            'page',
            $filters['page'] ?? 1
        );
}




//
    public function deleteOtherSessions(string $userId, string $currentSessionId): void
    {
        DB::table('sessions')
            ->where('user_id', $userId)
            ->where('id', '!=', $currentSessionId)
            ->delete();
    }








    //test function ooo
    public function DeleteUserByEmail (string $email)
    {
        return User::where('email', strtolower($email))->delete();

    }



}




