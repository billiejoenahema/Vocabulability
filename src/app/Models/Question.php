<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Requests\Question\IndexRequest;
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

    /**
     * 複数代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'word',
        'correct_answer',
        'example',
    ];

    /**
     * キャストする必要のある属性
     *
     * @var array
     */
    protected $casts = [
        'word' => 'string',
        'correct_answer' => 'string',
        'example' => 'string',
    ];

    /** @var array ソート対象カラム */
    public const SORTABLE_COLUMNS = [
        'word',
        'correct_answer',
    ];

    /** @var string デフォルトのソート対象カラム */
    public const DEFAULT_SORT_COLUMN = 'word';

    /**
     * 指定のカラムでソートするスコープ
     *
     * @param Builder|Question $query
     * @param IndexRequest $request
     */
    public function scopeSort($query, $request): Builder|self
    {
        $column = $request->getSortColumn();
        $order = $request->getSortOrder();

        if (in_array($column, self::SORTABLE_COLUMNS, false)) {
            $query->orderBy($column, $order);
        } else {
            $query->orderBy(self::DEFAULT_SORT_COLUMN, 'asc');
        }

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
