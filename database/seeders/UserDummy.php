<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UserDummy extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Muhammad Ricoasmara',
                'email' => 'rico',
                'perusahaan' => 'IT',
                'role' => 'admin',
                'password' => bcrypt('04oktober2000')
            ],
            [
                'name' => 'Nandar',
                'email' => 'nandar',
                'perusahaan' => 'SPV IT',
                'role' => 'superadmin',
                'password' => bcrypt('12345')
            ],
            [
                'name' => 'user1',
                'email' => 'user1',
                'perusahaan' => 'Markom',
                'role' => 'user',
                'password' => bcrypt('12345')
            ],
            [
                'name' => 'user2',
                'email' => 'user2',
                'perusahaan' => 'Human Resource',
                'role' => 'user',
                'password' => bcrypt('12345')
            ]
        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
