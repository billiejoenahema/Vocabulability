<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 一般ユーザーが問題を削除できないことを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCannotDeleteQuestion()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();

        $question = Question::factory()->create();
        $response = $this->actingAs($user)->deleteJson('/api/questions/' . $question->id);
        $response->assertForbidden();
    }

    /**
     * 管理者ユーザーが問題を削除できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanDeleteQuestion()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $question = Question::factory()->create();
        $response = $this->actingAs($user)->deleteJson('/api/questions/' . $question->id);
        $response->assertOk();
    }
}
