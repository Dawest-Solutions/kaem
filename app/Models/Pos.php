<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Pos extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'ph_id',
        'number_pos_main',
        'number_pos',
        'company_name',
    ];

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @return bool
     */
    public function getIsMainAttribute(): bool
    {
        return $this->number_pos_main === $this->number_pos;
    }

    /**
     * @return bool
     */
    public function getIsNetworkAttribute(): bool
    {
        return $this->posAdditional->isNotEmpty();
    }

    public function ph()
    {
        return $this->belongsTo(Ph::class,'ph_id')->withDefault([
            'name' => __('No data'),
        ]);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'pos_id');
    }

    /**
     * @param Period|null $period
     * @param string $type
     * @return HasMany
     */
    public function results(Period $period = null, string $type = 'firma'): HasMany
    {
        return $this->hasMany(Result::class, 'number_pos', 'number_pos')
            ->when($period, function ($query, $period) use ($type) {
                $query->where('period_id', '=', $period->id)->where('type', $type);
            });
    }

    /**
     * @return HasMany
     */
    public function prizeOrders(): HasMany
    {
        return $this->hasMany(PrizeOrder::class);
    }

    /**
     * @return HasMany
     */
    public function pos(): HasMany
    {
        return $this->hasMany(Pos::class, 'number_pos_main', 'number_pos');
    }


    /**
     * @return HasMany
     */
    public function posAdditional(): HasMany
    {
        return $this->pos()
            ->whereColumn('number_pos','!=', 'number_pos_main');
    }

    /**
     * @return HasMany
     */
    public function posNetwork(): HasMany
    {
        return $this->pos()
            ->withCount('pos')
            ->having('pos_count','>',1);
    }
}
