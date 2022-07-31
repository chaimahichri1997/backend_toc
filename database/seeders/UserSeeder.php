<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
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
        $faker = \Faker\Factory::create();
        // create user with role super-admin
        $userSuperAdmin = User::create(
            [
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => 'super.toc@yopmail.com',
                'country' => $faker->country(),
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role' => 'admins',
                'created_at' => now(),
                'updated_at' => now(),
                'customer_id' => '',
            ]
        );
        $userSuperAdmin->assignRole('super-admin');
        $userSuperAdmin->save();
    }
}
