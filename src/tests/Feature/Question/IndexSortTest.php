<?php

declare(strict_types=1);

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexSortTest extends TestCase
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
        Question::factory()->count(10)->create();
    }

    /**
     * 単語で一覧を昇順ソートできるか確認するテスト
     */
    public function test_sortIndexByWordAsc(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=word&is_asc=true');

        $response->assertStatus(200);
        $actual = collect($response->json('data'));
        $this->assertSame(
            $actual->sortBy('word')->pluck('word'),
            $actual->pluck('word')
        );
    }

    /**
     * 単語で一覧を降順ソートできるか確認するテスト
     */
    public function test_sortIndexByWordDesc(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=word&is_asc=false');

        $response->assertStatus(200);
        $actual = collect($response->json('data'));
        $this->assertSame(
            $actual->sortByDesc('word')->pluck('word'),
            $actual->pluck('word')
        );
    }

    /**
     * 正答で一覧を昇順ソートできるか確認するテスト
     */
    public function test_sortIndexByCorrectAnswerAsc(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=correct_answer&is_asc=true');

        $response->assertStatus(200);
        $actual = collect($response->json('data'));
        $this->assertSame(
            $actual->sortBy('correct_answer')->pluck('correct_answer'),
            $actual->pluck('correct_answer')
        );
    }

    /**
     * 正答で一覧を降順ソートできるか確認するテスト
     */
    public function test_sortIndexByCorrectAnswerDesc(): void
    {
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=correct_answer&is_asc=false');

        $response->assertStatus(200);
        $actual = collect($response->json('data'));
        $this->assertSame(
            $actual->sortByDesc('correct_answer')->pluck('correct_answer'),
            $actual->pluck('correct_answer')
        );
    }
}
