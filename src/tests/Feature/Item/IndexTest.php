<?php

declare(strict_types=1);

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\Precedent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();

        $items = Item::factory()->count(10)->create();
        foreach ($items as $item) {
            Precedent::factory()->create([
                'item_id' => $item->id,
            ]);
        }
    }

    /**
     * 一般ユーザーが項目一覧を取得できることを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCanGetItems()
    {
        // 実行
        $response = $this->actingAs($this->user)->get('/api/items');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }

    /**
     * 管理ユーザーが項目一覧を取得できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanGetItems()
    {
        // 実行
        $response = $this->actingAs($this->user)->get('/api/items');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }
}
