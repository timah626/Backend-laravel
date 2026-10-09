<?php

namespace App\Modules\Auth\Services;

use App\Modules\Users\Repository\UserRepository;

use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Auth;

use App\Exceptions\AppException;




class AuthService {
  public function __construct (
    private UserRepository $userRepository 
  ) {

  }
    

 public function login (array $data) {
    $user = $this -> userRepository-> findByEmail ($data ['email']);

    if ( !$user) {
        throw new AppException(
          'INVALID_CREDENTIALS',
          'invalid email or password',
           401
);
    }

    if  (!Hash::check($data['password'], $user->password)) {
         throw new AppException(
          'INVALID_CREDENTIALS',
          'invalid email or password',
           401
);
    }


     Auth::login($user);

      

     
      return ['user' => $user];


    }




    

    public function logout ( $request ) {

      Auth :: logout();

      $request -> session() -> invalidate ();

      $request -> session() -> regenerateToken();



    }


}








