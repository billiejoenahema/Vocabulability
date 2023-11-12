<?php

declare(strict_types=1);

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $question;
    private $data;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->question = Question::factory()->create();
        $this->data = [
            'word' => 'test',
            'correct_answer' => 'テスト更新',
        ];
    }

    /**
     * 一般ユーザーが問題を更新できないことを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCannotUpdateQuestion()
    {
        // 実行
        $response = $this->actingAs($this->user)->patchJson('/api/questions/' . $this->question->id, $this->data);
        $response->assertForbidden();
    }

    /**
     * 管理ユーザーが問題を更新できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanUpdateQuestion()
    {
        $this->user->is_admin = true;
        $this->user->save();

        // 実行
        $response = $this->actingAs($this->user)->patchJson('/api/questions/' . $this->question->id, $this->data);
        $response->assertOk();
        $this->assertDatabaseHas('questions', [
            'id' => $this->question->id,
            'word' => $this->data['word'],
            'correct_answer' => $this->data['correct_answer'],
        ]);
    }
}
