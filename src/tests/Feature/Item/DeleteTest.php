<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;

    /**
     * テスト前の共通処理
     */
    public function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->item = Item::factory()->create();
    }

    /**
     * 一般ユーザーが項目を削除できないことを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCannotDeleteItem()
    {
        $response = $this->actingAs($this->user)->deleteJson('/api/items/' . $this->item->id);
        $response->assertForbidden();
    }

    /**
     * 管理者ユーザーが項目を削除できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanDeleteItem()
    {
        $this->user->is_admin = true;
        $this->user->save();

        $response = $this->actingAs($this->user)->deleteJson('/api/items/' . $this->item->id);
        $response->assertOk();
    }
}
