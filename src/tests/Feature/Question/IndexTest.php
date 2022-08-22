<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    /**
     * テスト前の共通処理
     */
    public function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();

        Question::factory()->count(10)->create();
    }

    /**
     * 一般ユーザーが問題一覧を取得できることを確認するテスト。
     *
     * @return void
     */
    public function test_generalUserCanGetQuestions()
    {
        // 実行
        $response = $this->actingAs($this->user)->get('/api/questions');
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
        // 実行
        $response = $this->actingAs($this->user)->get('/api/questions');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }
}
