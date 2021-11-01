<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Ph extends Model
{
    use HasFactory;

    protected $table = 'ph';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_id',
        'rks_id',
        'name',
        'first_name',
        'last_name',
    ];

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    public function rks()
    {
        return $this->belongsTo(Rks::class,'rks_id');
    }

    /**
     * @return HasMany
     */
    public function poses(): HasMany
    {
        return $this->hasMany(Pos::class, 'ph_id');
    }

    /**
     * @return HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'number_pos', 'number_pos');
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
        return Result::whereIn('number_pos', $this->poses()->pluck('number_pos'))
            ->when($period, function($query, $period) {
                $query->where('period_id', '=', $period->id)->where('type', 'firma');
            })
            ->get();
    }
}
