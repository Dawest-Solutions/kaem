<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed results
 * @property mixed prizes
 */
class Result extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'number_pos_main',
        'number_pos',
        'period_id',
        'turnover',
        'threshold_basic',
        'threshold_silver',
        'threshold_gold',
        'basic_points',
        'silver_points',
        'gold_points',
        'lacking_points_basic',
        'lacking_points_silver',
        'lacking_points_gold',
        'active_points',
        'inactive_points',
        'status',
    ];

    public function pos()
    {
        return $this->belongsTo(Pos::class,'number_pos', 'number_pos');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

}
