<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Rks extends Model
{
    use HasFactory;

    protected $table = 'rks';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_id',
        'name',
    ];

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @return HasMany
     */
    public function phs(): HasMany
    {
        return $this->hasMany(Ph::class, 'rks_id');
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
    public function posAdditional(): HasMany
    {
        return $this->hasMany(Pos::class, 'number_pos_main', 'number_pos')
            ->whereColumn('number_pos','!=', 'number_pos_main');;

    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getTotalResults(Period $period = null)
    {
        return Result::whereIn('number_pos', Pos::whereIn('ph_id', $this->phs()->pluck('id'))->pluck('number_pos'))
            ->when($period, function($query, $period) {
                $query->where('period_id', '=', $period->id)->where('type', 'firma');
            })
            ->get();
    }

}
