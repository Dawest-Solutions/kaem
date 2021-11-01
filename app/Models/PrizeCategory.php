<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrizeCategory extends Model
{
    use HasFactory;

    protected $table = 'prize_categories';

    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsTo
     */
    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }
}
