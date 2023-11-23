<?php

declare(strict_types=1);

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
     * 項目名で一覧を降順ソートできるか確認するテスト
     */
    public function test_sortIndexByNameDesc(): void
    {
        /** @var string $column ソート対象のカラム */
        $column = 'name';
        $response = $this->actingAs($this->user)->getJson('/api/items?column=' . $column . '&is_asc=false');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $expected = $data->sortByDesc($column)->pluck($column)->values()->all();
        $actual = $data->pluck($column)->values()->all();

        $this->assertSame(
            $expected,
            $actual,
        );
    }

    /**
     * 項目名で一覧を昇順ソートできるか確認するテスト
     */
    public function test_sortIndexByNameAsc(): void
    {
        $column = 'name';
        $response = $this->actingAs($this->user)->getJson('/api/items?column=' . $column . '&is_asc=true');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $expected = $data->sortBy($column)->pluck($column)->values()->all();
        $actual = $data->pluck($column)->values()->all();

        $this->assertSame(
            $expected,
            $actual,
        );
    }
}
