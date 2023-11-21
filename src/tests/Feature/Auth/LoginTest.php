<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private $data;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed --class=UserSeeder');

        $this->correctData = [
            'email' => 'guest@example.com',
            'password' => 'guest_user',
        ];

        $this->incorrectData = [
            'email' => 'gest@example.com',
            'password' => 'guestuser',
        ];
    }

    /**
     * 正しいメールアドレスとパスワードでゲストログインできることを確認するテスト。
     */
    public function test_guestLoginSuccess(): void
    {
        $this->getJson(env('APP_URL') . '/sanctum/csrf-cookie');
        $this->postJson('/login', $this->correctData)->assertOk();
    }

    /**
     * 誤ったメールアドレスとパスワードではゲストログインできないことを確認するテスト。
     */
    public function test_guestLoginFailure()
    {
        $this->getJson(env('APP_URL') . '/sanctum/csrf-cookie');
        $this->postJson('/login', $this->incorrectData)->assertUnauthorized();
    }
}
