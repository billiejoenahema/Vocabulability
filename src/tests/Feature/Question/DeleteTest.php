<?php

declare(strict_types=1);

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $question;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->question = Question::factory()->create();
    }

    /**
     * 一般ユーザーが問題を削除できないことを確認するテスト。
     */
    public function test_generalUserCannotDeleteQuestion(): void
    {
        $response = $this->actingAs($this->user)->deleteJson('/api/questions/' . $this->question->id);
        $response->assertForbidden();
    }

    /**
     * 管理者ユーザーが問題を削除できることを確認するテスト。
     */
    public function test_adminUserCanDeleteQuestion(): void
    {
        $this->user->is_admin = true;
        $this->user->save();

        $response = $this->actingAs($this->user)->deleteJson('/api/questions/' . $this->question->id);
        $response->assertOk();
    }
}
