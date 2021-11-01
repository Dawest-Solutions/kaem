<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderStatuses extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(PrizeOrder::class);
    }
}
