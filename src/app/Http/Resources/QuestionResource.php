<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\Question $this */
        return [
            'id' => $this->id,
            'word' => $this->word,
            'correct_answer' => $this->correct_answer,
            'example' => $this->example,
        ];
    }
}
