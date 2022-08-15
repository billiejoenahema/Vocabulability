<?php

namespace Tests\Feature\Question;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 一般ユーザーが問題を追加できないことを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCannotPostQuestion()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/questions', [
            'word' => 'test',
            'correct_answer' => 'テスト',
            'example' => 'test is テスト',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('questions', 0);
    }

    /**
     * 管理ユーザーが問題を追加できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanPostQuestion()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        $response = $this->actingAs($user)->postJson('/api/questions', [
            'word' => 'test',
            'correct_answer' => 'テスト',
        ]);
        $response->assertCreated();
        $this->assertDatabaseCount('questions', 1);
    }
}
