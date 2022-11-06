<?php

namespace App\Models;

use App\Http\Requests\Item\IndexRequest;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Item
 *
 * @method static \Database\Factories\ItemFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Example[] $examples
 * @property-read int|null $examples_count
 * @method static \Illuminate\Database\Query\Builder|Item onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Item withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Item withoutTrashed()
 * @property int $id
 * @property string $name 項目名
 * @property string $category カテゴリ
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Precedent[] $precedents
 * @property-read int|null $precedents_count
 * @property string $name_kana 項目名ふりがな
 * @method static \Illuminate\Database\Eloquent\Builder|Item sortByNameKana()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereNameKana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item sortByIdDesc()
 * @method static \Illuminate\Database\Eloquent\Builder|Item sortByNameKanaAsc()
 * @method static \Illuminate\Database\Eloquent\Builder|Item sortByColumn($column, $order)
 * @method static \Illuminate\Database\Eloquent\Builder|Item searchCondition($request)
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
    ];

    /**
     * 所有する事例を取得する。
     *
     * @return HasMany
     *
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
     * @return Builder|Item
     */
    public function scopeSearchCondition($query, $request): Builder|Item
    {
        if (isset($request['keyword'])) {
            $query->where('name', 'like', "%{$request['keyword']}%");
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
     * @return Builder|Item
     */
    public function scopeSortByColumn($query, $column, $order): Builder|Item
    {
        $itemColumns = [
            'name',
        ];
        if (in_array($column, $itemColumns, false)) {
            $query->orderBy($column, $order);
        }
        return $query;
    }

    /**
     * IDの降順でソートするスコープ
     *
     * @param Builder|Item $query
     * @return Builder|Item
     */
    public function scopeSortByIdDesc($query): Builder|Item
    {
        $query->orderBy('id', 'desc');

        return $query;
    }
}
