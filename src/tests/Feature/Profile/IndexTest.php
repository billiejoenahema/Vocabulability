<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログインユーザーが自身のプロフィールを取得できることを確認するテスト。
     *
     * @return void
     */
    public function test_getAuthUserProfile()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/profile');
        $response->assertOk();
    }
}
