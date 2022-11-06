<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @method static \Database\Factories\QuestionFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereExample($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereWord($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Question sortByColumn($column, $order)
 * @method static \Illuminate\Database\Eloquent\Builder|Question sortByWordAsc()
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

    /**
     * 指定のカラムでソートするスコープ
     *
     * @param Builder|Question $query
     * @param string $column
     * @param string $order
     * @return Builder|Question
     */
    public function scopeSortByColumn($query, $column, $order): Builder|Question
    {
        $columns = [
            'word',
            'correct_answer',
        ];
        if (in_array($column, $columns, false)) {
            $query->orderBy($column, $order);
        }
        return $query;
    }

    /**
     * 単語の昇順でソートするスコープ
     *
     * @param Builder|Question $query
     * @return Builder|Question
     */
    public function scopeSortByWordAsc($query): Builder|Question
    {
        $query->orderBy('word', 'asc');

        return $query;
    }
}
