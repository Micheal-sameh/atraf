<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'super_admin',
            'membership_code' => 'E1C1F1NR1',
            'phone' => '01234567890',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('admin');

        $user1 = User::create([
            'name' => 'father',
            'membership_code' => 'E1C1F1NR2',
            'phone' => '01234567891',
            'password' => Hash::make('password'),
        ]);
        $user1->assignRole('father');

        $user2 = User::create([
            'name' => 'user',
            'membership_code' => 'E1C1F1NR3',
            'phone' => '01234567892',
            'password' => Hash::make('password'),
        ]);
        $user2->assignRole('user');
    }
}
