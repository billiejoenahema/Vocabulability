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
        // 本番環境なら何もしない
        if (env('APP_ENV') === 'production') {
            return;
        }
        // ゲストユーザー
        User::create([
            'name' => 'guest_user',
            'email' => 'guest@example.com',
            'password' => Hash::make('guest_user'),
            'is_admin' => false,
        ]);
    }
}
