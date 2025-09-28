<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'], // 検索条件
            [
                'name' => 'test',
                'password' => Hash::make('password'), // ここにまとめて渡す
                'email_verified_at' => now(),         // 任意
            ]
        );
    }
}
