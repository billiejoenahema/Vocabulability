<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\Precedent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexSortTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    /**
     * テスト前の共通処理
     */
    public function setUp(): void
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
        $itemsAsc = collect($items)->sortBy('name')->values()->all();
        $this->nameAsc = $itemsAsc[0]->name;
        $itemsDesc = collect($items)->sortByDesc('name')->values()->all();
        $this->nameDesc = $itemsDesc[0]->name;
    }

    /**
     * 項目名で一覧を昇順ソートできるか確認するテスト
     *
     * @return void
     */
    public function test_sortIndexByNameAsc()
    {
        $response = $this->actingAs($this->user)->getJson('/api/items?column=name&is_asc=true');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', $this->nameAsc);
    }

    /**
     * 項目名で一覧を降順ソートできるか確認するテスト
     *
     * @return void
     */
    public function test_sortIndexByNameDesc()
    {
        $response = $this->actingAs($this->user)->getJson('/api/items?column=name&is_asc=false');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', $this->nameDesc);
    }
}
