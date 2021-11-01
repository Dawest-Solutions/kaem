<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Collection;

/**
 * @property mixed results
 * @property mixed prizes
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'pos_id',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_visit' => 'datetime',
    ];

    /**
     * Roles available with configuration.
     * @return Collection
     */
    public static function availableRoles(): Collection
    {
        return collect(config('roles'));
    }

    // Accessors & Mutators

    public function setFirstNameAttribute($value): string
    {
        return $this->attributes['first_name'] = ucwords(mb_strtolower($value, 'UTF-8'));
    }

    /**
     * @param $value
     */
    public function setLastNameAttribute($value): string
    {
        return $this->attributes['last_name'] = ucwords(mb_strtolower($value, 'UTF-8'));
    }

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getPreviousSystemUserAttribute(): bool
    {
        // TODO add config
        return Carbon::create('5.11.2021')->gte(now());
    }

    // Relationships

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
    public function periods(): HasMany
    {
        return $this->hasMany(Period::class);
    }

    /**
     * @return HasMany
     */
    public function poses(): HasMany
    {
        return $this->hasMany(Pos::class, 'user_id', 'id');
    }

    public function pos()
    {
        return $this->belongsTo(Pos::class,'pos_id');
    }

    // Scopes

    public function scopeRegisterByCode($query, $code)
    {
        return $query->where('register_code', $code)
            ->whereNotNull('register_code');
    }

}
