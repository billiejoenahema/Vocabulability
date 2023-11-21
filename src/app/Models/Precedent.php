<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Precedent
 *
 * @property int $id
 * @property int $item_id 項目ID
 * @property string $name 名称
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item|null $item
 * @method static \Database\Factories\PrecedentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent query()
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Precedent withoutTrashed()
 * @mixin \Eloquent
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
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
