<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Example
 *
 * @method static \Database\Factories\ExampleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Example newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Example newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Example query()
 * @mixin \Eloquent
 * @property-read \App\Models\Item|null $item
 * @method static \Illuminate\Database\Query\Builder|Example onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Example withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Example withoutTrashed()
 * @property int $id
 * @property int $item_id 項目ID
 * @property string $name 名称
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Example whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Example whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Example whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Example whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Example whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Example whereUpdatedAt($value)
 */
class Precedent extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'name',
    ];

    /**
     * 紐づく項目を取得する。
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
