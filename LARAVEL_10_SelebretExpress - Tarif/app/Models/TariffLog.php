<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TariffLog extends Model
{
    protected $fillable = [
        'tariff_id',
        'old_price',
        'new_price',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'old_price'  => 'float',
        'new_price'  => 'float',
        'changed_at' => 'datetime',
    ];

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }
}