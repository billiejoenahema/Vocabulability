<?php

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
    public function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->data = [
            'name' => 'テスト',
            'category' => '01',
            'precedents' => [
                ['name' => 'test1'],
                ['name' => 'test2'],
            ],
        ];
    }

    /**
     * 一般ユーザーが項目を追加できないことを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCannotPostItem()
    {
        $response = $this->actingAs($this->user)->postJson('/api/items', $this->data);

        $response->assertForbidden();
        $this->assertDatabaseCount('items', 0);
    }

    /**
     * 管理ユーザーが項目を追加できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanPostItem()
    {
        $this->user->is_admin = true;
        $this->user->save();

        $response = $this->actingAs($this->user)->postJson('/api/items', $this->data);
        $response->assertCreated();
        $this->assertDatabaseCount('items', 1);
    }
}
