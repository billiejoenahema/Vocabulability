<?php

declare(strict_types=1);

namespace Tests\Feature\Item;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $data;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->data = [
            'name' => 'テスト',
            'name_kana' => 'てすと',
            'category' => '01',
            'precedents' => [
                ['name' => 'test1'],
                ['name' => 'test2'],
            ],
        ];
    }

    /**
     * 一般ユーザーが項目を追加できないことを確認するテスト。
     */
    public function test_generalUserCannotPostItem(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/items', $this->data);

        $response->assertForbidden();
        $this->assertDatabaseCount('items', 0);
    }

    /**
     * 管理ユーザーが項目を追加できることを確認するテスト。
     */
    public function test_adminUserCanPostItem(): void
    {
        $this->user->is_admin = true;
        $this->user->save();

        $response = $this->actingAs($this->user)->postJson('/api/items', $this->data);
        $response->assertCreated();
        $this->assertDatabaseCount('items', 1);
    }
}
