<?php

namespace Database\Seeders;

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
        // 一般ユーザー
        User::create([
            'name' => 'general_user',
            'email' => 'user@example.com',
            'password' => Hash::make('11111111'),
            'is_admin' => false,
        ]);
    }
}
