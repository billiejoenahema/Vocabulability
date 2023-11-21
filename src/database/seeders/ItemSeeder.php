<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Precedent;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 開発環境でのみ実行する
        if (env('APP_ENV') !== 'local') {
            return;
        }
        $items = Item::factory()->count(10)->create();
        foreach ($items as $item) {
            Precedent::factory()->count(2)->create([
                'item_id' => $item->id,
            ]);
        }
    }
}
