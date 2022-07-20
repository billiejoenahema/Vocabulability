<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 問題を更新できるかどうかをテストする。
     *
     * @return void
     */
    public function test_updateQuestion()
    {
        $question = Question::factory()->create();
        $data = [
            'word' => 'test',
            'correct_answer' => 'テスト更新',
            'example' => 'test_update is テスト更新',
        ];

        // 実行
        $response = $this->patchJson('/api/questions/' . $question->id, $data);
        $response->assertOk();
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'word' => $data['word'],
            'correct_answer' => $data['correct_answer'],
            'example' => $data['example'],
        ]);
    }
}
