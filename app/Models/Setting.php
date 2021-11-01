<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Settings available with configuration.
     * @return array
     */
    public static function availableSettings(): Collection
    {
        return collect(config('settings'));
    }

    /**
     * @param Builder $query
     * @param string $key
     * @return string|null
     */
    public function scopeGetValueByKey($query, string $key)
    {
        return $query->where('key', $key);
    }
}
