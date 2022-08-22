<?php

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
    public function setUp(): void
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
     *
     * @return void
     */
    public function test_guestLoginSuccess()
    {
        $this->getJson('http://localhost:8080/sanctum/csrf-cookie');
        $this->postJson('/login', $this->correctData)->assertOk();
    }

    /**
     * 誤ったメールアドレスとパスワードではゲストログインできないことを確認するテスト。
     *
     * @return void
     */
    public function test_guestLoginFailure()
    {
        $this->getJson('http://localhost:8080/sanctum/csrf-cookie');
        $this->postJson('/login', $this->incorrectData)->assertUnauthorized();
    }
}
