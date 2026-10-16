<?php

namespace App\Modules\Users\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Modules\Organisation\Models\Department;

use App\Modules\Onboarding\Models\Application;

use App\Modules\Organisation\Models\Site;



use Illuminate\Database\Eloquent\Concerns\HasUlids;

use
Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]


class User extends Authenticatable
{
     /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUlids;

      protected $fillable = [
    'name', 'email', 'password', 'school', 'level', 'major',
    'site_id', 'department_id', 'start_date', 'end_date', 'photo_path',
];






     public function suggestedDepartment()

        {
            return $this->belongsTo(Department::class, 'department_id');
        }





    public function site()
    {
         return $this->belongsTo(Site::class, 'site_id');
    }



    public function application()
{
    return $this->hasOne(Application::class, 'user_id');
}
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
     protected function casts(): array





{
    return [
        'password' => 'hashed',
        'active' => 'boolean',
        'temp_password_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
}
