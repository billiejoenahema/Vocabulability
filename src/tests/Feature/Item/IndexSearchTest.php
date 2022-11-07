<?php

namespace Tests\Feature\Item;

use App\Models\Item;
use App\Models\Precedent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class IndexSearchTest extends TestCase
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
        $this->created_at_from = Carbon::parse(now())->subDay()->format('Y-m-d H:i:s');
        $this->created_at = Carbon::parse($items[9]->created_at)->format('Y-m-d H:i:s');
    }

    /**
     * created_atで一覧を検索できるか確認するテスト
     *
     * @return void
     */
    public function test_sortIndexByCreated_at()
    {
        $response = $this->actingAs($this->user)->getJson('/api/items?created_at_from=' . $this->created_at_from);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.created_at', $this->created_at);
    }
}
