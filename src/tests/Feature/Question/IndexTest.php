<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 問題一覧を取得できるかどうかをテストする。
     *
     * @return void
     */
    public function test_getQuestions()
    {
        Question::factory()->count(10)->create();

        // 実行
        $response = $this->get('/api/questions');
        $response
            ->assertOk()
            ->assertJsonCount(10, 'data');
    }
}
