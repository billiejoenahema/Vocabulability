<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 一般ユーザーが問題一覧を取得できることを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCanGetQuestions()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        Question::factory()->count(10)->create();

        // 実行
        $response = $this->actingAs($user)->get('/api/questions');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }

    /**
     * 管理ユーザーが問題一覧を取得できることを確認するテスト。
     *
     * @return void
     */
    public function test_adminUserCanGetQuestions()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create();
        Question::factory()->count(10)->create();

        // 実行
        $response = $this->actingAs($user)->get('/api/questions');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }
}
