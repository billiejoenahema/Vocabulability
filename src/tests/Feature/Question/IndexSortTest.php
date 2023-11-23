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
        $column = 'word';
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=' . $column . '&is_asc=true');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $expected = $data->sortBy($column)->pluck($column)->values()->all();
        $actual = $data->pluck($column)->values()->all();

        $this->assertSame(
            $expected,
            $actual,
        );
    }

    /**
     * 単語で一覧を降順ソートできるか確認するテスト
     */
    public function test_sortIndexByWordDesc(): void
    {
        $column = 'word';
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=' . $column . '&is_asc=false');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $expected = $data->sortByDesc($column)->pluck($column)->values()->all();
        $actual = $data->pluck($column)->values()->all();

        $this->assertSame(
            $expected,
            $actual,
        );
    }

    /**
     * 正答で一覧を昇順ソートできるか確認するテスト
     */
    public function test_sortIndexByCorrectAnswerAsc(): void
    {
        $column = 'correct_answer';
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=' . $column . '&is_asc=true');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $expected = $data->sortBy($column)->pluck($column)->values()->all();
        $actual = $data->pluck($column)->values()->all();

        $this->assertSame(
            $expected,
            $actual,
        );
    }

    /**
     * 正答で一覧を降順ソートできるか確認するテスト
     */
    public function test_sortIndexByCorrectAnswerDesc(): void
    {
        $column = 'correct_answer';
        $response = $this->actingAs($this->user)->getJson('/api/questions?column=' . $column . '&is_asc=false');

        $response->assertStatus(200);
        $data = collect($response->json('data'));
        $expected = $data->sortByDesc($column)->pluck($column)->values()->all();
        $actual = $data->pluck($column)->values()->all();

        $this->assertSame(
            $expected,
            $actual,
        );
    }
}
