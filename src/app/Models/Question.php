<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function in_array;


/**
 * App\Models\Question
 *
 * @property int $id
 * @property string $word 単語
 * @property string $correct_answer 正解
 * @property string|null $example 例文
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question randomSort()
 * @method static \Illuminate\Database\Eloquent\Builder|Question sort($column, $order)
 * @method static \Illuminate\Database\Eloquent\Builder|Question sortByWordAsc()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereExample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereWord($value)
 * @mixin \Eloquent
 */
class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'word',
        'correct_answer',
        'example',
    ];

    protected $casts = [
        'word' => 'string',
        'correct_answer' => 'string',
        'example' => 'string',
    ];

    /** @var array ソート可能なカラムリスト */
    public const SORTABLE_COLUMNS = [
        'word',
        'correct_answer',
    ];

    /**
     * 指定のカラムでソートするスコープ
     *
     * @param Builder|Question $query
     * @param string $column
     * @param string $order
     */
    public function scopeSort($query, $column, $order): Builder|self
    {
        if (in_array($column, self::SORTABLE_COLUMNS, false)) {
            $query->orderBy($column, $order);
        }
        return $query;
    }

    /**
     * 単語の昇順でソートするスコープ
     *
     * @param Builder|Question $query
     */
    public function scopeSortByWordAsc($query): Builder|self
    {
        $query->orderBy('word', 'asc');

        return $query;
    }

    /**
     * ランダムな並びでソートするスコープ
     *
     * @param Builder|Question $query
     */
    public function scopeRandomSort($query): Builder|self
    {
        $query->inRandomOrder();

        return $query;
    }
}
