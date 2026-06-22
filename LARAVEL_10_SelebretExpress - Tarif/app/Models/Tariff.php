<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    protected $fillable = [
        'origin_city',
        'dest_city',
        'price_per_kg',
        'min_weight_kg',
        'estimated_days',
        'is_active',
    ];

    protected $casts = [
        'price_per_kg'   => 'float',
        'min_weight_kg'  => 'float',
        'is_active'      => 'boolean',
        'estimated_days' => 'integer',
    ];

    // Relasi ke log perubahan (dalam DB yang sama)
    public function logs()
    {
        return $this->hasMany(TariffLog::class);
    }

    // Scope: hanya tarif yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}