<?php

namespace App\Modules\Users\Repository;

use App\Modules\Users\Models\User;

class userRepository {

public function findByEmail (string $email)  :?User { //chelsie na wow o. gotta return a user object

return User :: where('email', $email)-> first ();
}

public function create ( array $data) :?User{
    return User::create ($data);
}
}




