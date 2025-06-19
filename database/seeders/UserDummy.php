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
                'name' => 'Admin Ops Maxima 1',
                'email' => 'ana@maximagroup.co.id',
                'company' => 'Maxima',
                'phone' => '62895321678104',
                'role' => 'admin',
                'password' => bcrypt('4dm1nMaxima')
            ],
            [
                'name' => 'Admin Ops Maxima 2',
                'email' => 'temank3.maxima@gmail.com',
                'company' => 'Maxima',
                'phone' => '6285283051168',
                'role' => 'admin',
                'password' => bcrypt('0psM4xima')
            ],
            [
                'name' => 'Dewa Dev Kps',
                'email' => 'tmspprt.lpg@gmail.com',
                'company' => 'Langgeng Perkasa Group',
                'phone' => '628118509001',
                'role' => 'dev',
                'password' => bcrypt('DevLp6Kps')
            ],
            [
                'name' => 'Management LPG',
                'email' => 'management@gmail.com',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'management',
                'password' => bcrypt('LPGr0up1')
            ],
            [
                'name' => 'Management LPG 2',
                'email' => 'management1@gmail.com',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'management',
                'password' => bcrypt('LPGr0up1')
            ],
             [
                'name' => 'User',
                'email' => 'user1',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'user',
                'password' => bcrypt('12345')
             ],
             [
                'name' => 'User2',
                'email' => 'user2',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'user',
                'password' => bcrypt('12345')
            ]
        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
