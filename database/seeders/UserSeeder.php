<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => '1',
            'name' => 'Administrator',
            'email' => 'admin@jek.co.id',
            'password' => bcrypt('admin'),
            'role' => 'admin',
        ]);

        User::create([
            'id' => '2',
            'name' => 'Orang',
            'email' => 'orang@gmail.com',
            'password' => bcrypt('lupacoy'),
            'role' => 'user',
        ]);

        User::create([
            'id' => '3',
            'name' => 'User',
            'email' => 'user1132@gmail.com',
            'password' => bcrypt('apayalupa'),
            'role' => 'user',
        ]);

        User::create([
            'id' => '4',
            'name' => 'Anton',
            'email' => 'antongtg@gmail.com',
            'password' => bcrypt('antonisasi'),
            'role' => 'admin',

        ]);
    }
}
