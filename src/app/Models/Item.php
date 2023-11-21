<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Requests\Item\IndexRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use function in_array;


/**
 * App\Models\Item
 *
 * @property int $id
 * @property string $name 項目名
 * @property string $name_kana 項目名ふりがな
 * @property string $category カテゴリ
 * @property string|null $description 説明
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Precedent> $precedents
 * @property-read int|null $precedents_count
 * @method static \Database\Factories\ItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item searchCondition($request)
 * @method static \Illuminate\Database\Eloquent\Builder|Item sort($column, $order)
 * @method static \Illuminate\Database\Eloquent\Builder|Item sortByIdDesc()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNameKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Item withoutTrashed()
 * @mixin \Eloquent
 */
class Item extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_kana',
        'category',
        'description',
    ];

    /** @var array ソート可能なカラムリスト */
    public const SORTABLE_COLUMNS = [
        'id',
        'name_kana',
        'description',
    ];

    /**
     * 所有する事例を取得する。
     */
    public function precedents(): HasMany
    {
        return $this->hasMany(Precedent::class);
    }

    /**
     * 検索条件
     *
     * @param Builder|Item $query
     * @param IndexRequest $request
     */
    public function scopeSearchCondition($query, $request): Builder|self
    {
        if ($request['keyword']) {
            $query->where('name', 'like', "%{$request['keyword']}%")
                ->orWhere('description', 'like', "{$request['keyword']}%");
        }
        if (isset($request['filter'])) {
            $query->where('name_kana', 'like', "{$request['filter']}%");
        }

        return $query;
    }

    /**
     * 指定のカラムでソートするスコープ
     *
     * @param Builder|Item $query
     * @param string $column
     * @param string $order
     */
    public function scopeSort($query, $column, $order): Builder|self
    {
        if (in_array($column, self::SORTABLE_COLUMNS, false)) {
            $query->orderByRaw("{$column} is null asc")->orderBy($column, $order);
        }

        return $query;
    }

    /**
     * IDの降順でソートするスコープ
     *
     * @param Builder|Item $query
     */
    public function scopeSortByIdDesc($query): Builder|self
    {
        $query->orderBy('id', 'desc');

        return $query;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
