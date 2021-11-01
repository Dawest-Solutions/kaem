<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method spentPoints()
 */
class PrizeOrder extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'datetime:Y-m-d'
    ];

    protected $fillable =[
        'id',
        'prize_id',
        'user_id',
        'pos_id',
        'value',
        'saldo',
        'order_date',
        'release_date',
        'days_from_order',
        'days_late',
        'tax_declaration',
        'status_id',
        'full_name',
        'phone',
        'email',
        'address',
        'postal_code',
        'city',
    ];

    /**
     * @return BelongsTo
     */
    public function prize(): BelongsTo
    {
        return $this->belongsTo(Prize::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pos(): BelongsTo
    {
        return $this->belongsTo(Pos::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatuses::class);
    }
}
