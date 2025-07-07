<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Admin-Ops1',
                'email' => 'ana@maximagroup.co.id',
                'company' => 'Maxima',
                'phone' => '62895321678104',
                'role' => 'admin',
                'password' => bcrypt('4dm1nMaxima')
            ],
            [
                'name' => 'Admin-Ops2',
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
                'email' => 'management@maximagroup.com',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'management',
                'password' => bcrypt('LPGr0up25')
            ],
            [
                'name' => 'Management LPG 2',
                'email' => 'management1@maximagroup.com',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'management',
                'password' => bcrypt('m4n49ement')
            ],
            [
                'name' => 'user1',
                'email' => 'user1',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'user',
                'password' => bcrypt('12345')
            ],
            [
                'name' => 'user2',
                'email' => 'user2',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'admin',
                'password' => bcrypt('12345')
            ],
            [
                'name' => 'admin1',
                'email' => 'admin1',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'admin',
                'password' => bcrypt('54321')
            ],
            [
                'name' => 'admin2',
                'email' => 'admin2',
                'company' => 'Langgeng Perkasa Group',
                'role' => 'admin',
                'password' => bcrypt('54321')
            ],
        ];
        foreach ($userData as $key => $val) {
            User::create($val);
        }
    }
}
