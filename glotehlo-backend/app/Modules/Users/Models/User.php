<?php

namespace App\Modules\Users\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


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
