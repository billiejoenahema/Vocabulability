<?php

declare(strict_types=1);

namespace Tests\Feature\Question;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $data;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $this->data = [
            'word' => 'test',
            'correct_answer' => 'テスト',
        ];
    }

    /**
     * 一般ユーザーが問題を追加できないことを確認するテスト。
     */
    public function test_generalUserCannotPostQuestion(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/questions', $this->data);

        $response->assertForbidden();
        $this->assertDatabaseCount('questions', 0);
    }

    /**
     * 管理ユーザーが問題を追加できることを確認するテスト。
     */
    public function test_adminUserCanPostQuestion(): void
    {
        $this->user->is_admin = true;
        $this->user->save();

        $response = $this->actingAs($this->user)->postJson('/api/questions', $this->data);
        $response->assertCreated();
        $this->assertDatabaseCount('questions', 1);
    }
}
