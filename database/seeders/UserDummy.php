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
                'email' => 'admin1',
                'perusahaan' => 'IT',
                'role' => 'admin',
                'password' => bcrypt('54321')
            ],
            [
                'name' => 'Nandar',
                'email' => 'admin2',
                'perusahaan' => 'SPV IT',
                'role' => 'admin',
                'password' => bcrypt('54321')
            ],
            [
                'name' => 'Uknown',
                'email' => 'admin3',
                'perusahaan' => 'SPV IT',
                'role' => 'admin',
                'password' => bcrypt('54321')
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
            ],
            [
                'name' => 'user3',
                'email' => 'user3',
                'perusahaan' => 'Human Resource',
                'role' => 'user',
                'password' => bcrypt('12345')
            ],
            [
                'name' => 'user4',
                'email' => 'user4',
                'perusahaan' => 'Human Resource',
                'role' => 'user',
                'password' => bcrypt('12345')
            ],
            [
                'name' => 'Dewa Ennel',
                'email' => 'management1',
                'perusahaan' => 'Dewa',
                'role' => 'management',
                'password' => bcrypt('12345')
            ]
        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
