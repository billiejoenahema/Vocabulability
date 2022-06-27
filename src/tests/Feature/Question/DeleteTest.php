<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
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
