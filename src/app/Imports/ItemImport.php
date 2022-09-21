<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Precedent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ItemImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $item = Item::create([
                'category' => '01',
                'name' => $row[0], // 行の1列目
                'name_kana' => $row[1], // 行の2列目
            ]);
            Precedent::create([
                'item_id' => $item->id,
                'name' => $row[2], // 行の3列目
            ]);
            if (!empty($row[3])) {
                Precedent::create([
                    'item_id' => $item->id,
                    'name' => $row[3], // 行の4列目
                ]);
            }
        }
    }
}
