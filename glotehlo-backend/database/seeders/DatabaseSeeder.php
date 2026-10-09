<?php

namespace Database\Seeders;

use App\Modules\Organisation\Models\Site;
use App\Modules\Organisation\Models\Department;
use App\Modules\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Model::unguard();

        $site = Site::firstOrCreate(
            ['name' => 'Glotehlo HQ'],
            ['timezone' => 'Africa/Douala', 'start_time' => '08:00']
        );

        $engineering = Department::firstOrCreate(
            ['site_id' => $site->id, 'name' => 'Engineering'],
            ['qr_slug' => 'eng-hq']
        );

        $marketing = Department::firstOrCreate(
            ['site_id' => $site->id, 'name' => 'Marketing'],
            ['qr_slug' => 'mkt-hq']
        );

        User::firstOrCreate(
            ['email' => 'admin@glotehlo.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'head@glotehlo.com'],
            [
                'name' => 'Head-Engineering',
                'password' => Hash::make('password123'),
                'role' => 'intern_head',
                'site_id' => $site->id,
                'department_id' => $engineering->id,
            ]
        );

        foreach (range(1, 20) as $n) {
            User::firstOrCreate(
                ['email' => "intern$n@glotehlo.com"],
                [
                    'name' => "Intern $n",
                    'password' => Hash::make('password'),
                    'role' => 'intern',
                    'site_id' => $site->id,
                    'department_id' => $n <= 10 ? $engineering->id : $marketing->id,
                ]
            );
        }

        Model::reguard();
    }
}
