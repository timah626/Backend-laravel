<?php

namespace App\Modules\Auth\services;

use App\Modules\Users\Repository\UserRepository;

use Illuminate\Support\Facades\Hash;
use Exception;

use Illuminate\Support\Facades\Auth;




class AuthService {
  public function __construct (
    private UserRepository $userRepository 
  ) {

  }
    

 public function login (array $data) {
    $user = $this -> userRepository-> findByEmail ($data ['email']);

    if ( !$user) {
        throw new Exception ("user not found");
    }

    if  (!Hash::check($data['password'], $user->password)) {
        throw new Exception ('invalid credentials');
    }


     Auth::login($user);

      

      print_r('printing user now');
      return ['user' => $user];


    }




    

    public function logout ( $request ) {

      Auth :: logout();

      $request -> session() -> invalidate ();

      $request -> session() -> regenerateToken();



    }


}








