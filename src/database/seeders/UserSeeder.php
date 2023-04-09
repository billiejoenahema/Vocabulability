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
        // 開発環境でのみ実行する
        if (env('APP_ENV') !== 'local') {
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
