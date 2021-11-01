<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Period extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'number',
    ];

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function resultsByPos(): HasMany
    {
        $pos = Auth::user()
            ->pos()
            ->first();

        if (is_null($pos)) {
            throw new IsNotAssigned(__('POS is not linked to your account.'), 500);
        }

        return $this->hasMany(Result::class)
            ->where('number_pos', $pos->number_pos);
    }

    public function isCurrent()
    {
        return now()->between($this->begin_at, $this->end_at);
    }

    public static function current(): ?Period
    {
        return self::where('begin_at', '<=', now())
            ->where('end_at', '>=', now())
            ->first();
    }

    public static function getFromSession(): ?Period
    {
        return Period::find(session()->get('period_id')) ?: self::current();
    }
}
