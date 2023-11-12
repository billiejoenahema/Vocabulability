<?php

declare(strict_types=1);

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * テスト前の共通処理
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $this->user = User::factory()->create();
        $question = Question::factory()->count(10)->create();

        $this->word = $question[0]->word;
        $this->correct_answer = $question[0]->correct_answer;
    }

    /**
     * 単語で一覧を検索できるか確認するテスト
     *
     * @return void
     */
    public function test_searchIndexByWord()
    {
        $response = $this->actingAs($this->user)->getJson('/api/questions?keyword=' . $this->word);

        $response->assertStatus(200)
            ->assertJsonPath('data.0.word', $this->word);
    }
}
