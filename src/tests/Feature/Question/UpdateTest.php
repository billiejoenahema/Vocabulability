<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 一般ユーザーが問題を更新できないことを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCannotUpdateQuestion()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();

        $question = Question::factory()->create();
        $data = [
            'word' => 'test',
            'correct_answer' => 'テスト更新',
        ];

        // 実行
        $response = $this->actingAs($user)->patchJson('/api/questions/' . $question->id, $data);
        $response->assertForbidden();
    }

    /**
     * 管理ユーザーが問題を更新できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanUpdateQuestion()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $question = Question::factory()->create();
        $data = [
            'word' => 'test',
            'correct_answer' => 'テスト更新',
        ];

        // 実行
        $response = $this->actingAs($user)->patchJson('/api/questions/' . $question->id, $data);
        $response->assertOk();
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'word' => $data['word'],
            'correct_answer' => $data['correct_answer'],
        ]);
    }
}
