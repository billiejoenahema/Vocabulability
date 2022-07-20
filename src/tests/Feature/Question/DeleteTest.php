<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 問題を削除できるかどうかをテストする。
     *
     * @return void
     */
    public function test_deleteQuestion()
    {
        $question = Question::factory()->create();
        $response = $this->deleteJson('/api/questions/' . $question->id);
        $response->assertOk();
    }
}
