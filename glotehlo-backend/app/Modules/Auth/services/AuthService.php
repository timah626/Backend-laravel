<?php

namespace App\Modules\Auth\services;

use App\Modules\Users\Repository\UserRepository;

use Illuminate\Support\Facades\Hash;
use Exception;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Modules\Users\Models\User;



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

      


      if( $user -> role == 'admin'){
        print_r('logged in sucessfully');
      };
       print_r('printing user now');
      return ['user' => $user];


    }




    

    public function logout ( $request ) {

      Auth :: logout();

      $request -> session() -> invalidate ();

      $request -> session() -> regenerateToken();



    }


}








