<?php

namespace Database\Seeders;

use App\Models\Precedent;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
