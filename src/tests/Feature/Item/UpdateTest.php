<?php

declare(strict_types=1);

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;
    private $data;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->item = Item::factory()->create();
        $this->precedent = $this->item->precedents()->createMany([['item_id' => $this->item->id, 'name' => 'test1']]);
        $this->data = [
            'name' => 'テスト',
            'name_kana' => $this->item->name_kana,
            'category' => '01',
            'precedents' => [
                [
                    'id' => $this->precedent[0]->id,
                    'item_id' => $this->item->id,
                    'name' => 'test1_updated!',
                ],
                [
                    'id' => null,
                    'item_id' => $this->item->id,
                    'name' => 'test2'
                ],
                [
                    'id' => null,
                    'item_id' => $this->item->id,
                    'name' => 'test3'
                ],
            ],
        ];
    }

    /**
     * 一般ユーザーが項目を更新できないことを確認するテスト。
     */
    public function test_generalUserCannotUpdateItem(): void
    {
        // 実行
        $response = $this->actingAs($this->user)->patchJson('/api/items/' . $this->item->id, $this->data);
        $response->assertForbidden();
    }

    /**
     * 管理ユーザーが項目を更新できることを確認するテスト。
     */
    public function test_adminUserCanUpdateItem(): void
    {
        $this->user->is_admin = true;
        $this->user->save();

        // 実行
        $response = $this->actingAs($this->user)->patchJson('/api/items/' . $this->item->id, $this->data);
        $response->assertOk();
        $this->assertDatabaseHas('items', [
            'id' => $this->item->id,
            'name' => $this->data['name'],
        ]);
        $this->assertDatabaseHas('precedents', [
            'id' => $this->precedent[0]->id,
            'item_id' => $this->precedent[0]->item_id,
            'name' => $this->data['precedents'][0]['name'],
        ]);
    }
}
