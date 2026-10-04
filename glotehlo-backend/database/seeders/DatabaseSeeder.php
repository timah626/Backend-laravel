<?php

namespace Database\Seeders;


use App\Modules\Organisation\Models\Site;
use App\Modules\Organisation\Models\Department;
use App\Modules\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // 1. the site
    $site = Site::forceCreate([
        'name' => 'Glotehlo HQ',
        'timezone' => 'Africa/Douala',
        'start_time' => '08:00',
    ]);

    // 2. two departments, each needs the site's id
    $engineering = Department::forceCreate([
        'site_id' => $site->id,
        'name' => 'Engineering',
        'qr_slug' => 'eng-hq',
    ]);

    $marketing = Department::forceCreate([
        'site_id' => $site->id,
        'name' => 'Marketing',
        'qr_slug' => 'mkt-hq',
    ]);

    // 3. admin: no site, no department
    User::forceCreate([
        'name' => 'Admin',
        'email' => 'admin@glotehlo.com',
        'password' => 'password123',
        'role' => 'admin',
    ]);

    // 4. intern head in Engineering
    User::forceCreate([
        'name' => 'Head-Engineering',
        'email' => 'head@glotehlo.com',
        'password' => 'password123',
        'role' => 'intern_head',
        'site_id' => $site->id,
        'department_id' => $engineering->id,
    ]);

    
   

    foreach (range(1, 10) as $n) {
    User::forceCreate([
        'name' => "Intern $n",
        'email' => "intern$n@glotehlo.com",
        'password' => 'password',
        'role' => 'intern',
        'site_id' => $site->id,
        'department_id' => $engineering->id,
    ]);
}




}
}