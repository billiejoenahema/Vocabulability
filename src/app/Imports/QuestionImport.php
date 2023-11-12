<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Question::create([
                'word' => $row[0], // 行の1列目
                'correct_answer' => $row[1], // 行の2列目
            ]);
        }
    }
}
