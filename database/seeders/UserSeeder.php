<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = ['created_at' => now(), 'updated_at' => now()];

        User::create([
            'name' => 'Md. Asif Rahman',
            'email' => 'eng.asifrahman@gmail.com',
            'phone' => '01910241412',
            'currency' => 'USD',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Eva Akter Meri',
            'email' => 'evaaktermeri@gmail.com',
            'phone' => '01610241412',
            'currency' => 'EUR',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);
    }
}
