<?php

namespace Tests\Feature\Question;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 問題を追加できるかどうかをテストする。
     *
     * @return void
     */
    public function test_postQuestion()
    {
        $response = $this->postJson('/api/questions', [
            'word' => 'test',
            'correct_answer' => 'test',
            'example' => 'test',
        ]);

        $response->assertCreated();

        $this->assertDatabaseCount('questions', 1);
    }
}
