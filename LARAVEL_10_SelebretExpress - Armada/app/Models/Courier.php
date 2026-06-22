<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'warehouse_id',
        'name',
        'phone',
        'vehicle_type',
        'vehicle_plate',
        'status',
        'last_active_at',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    // Satu kurir bisa punya banyak delivery
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    // Delivery aktif (belum selesai)
    public function activeDeliveries()
    {
        return $this->hasMany(Delivery::class)
                    ->whereNotIn('status', ['delivered', 'failed', 'returned']);
    }

    // Scope: kurir yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope: kurir yang sedang bertugas
    public function scopeOnDuty($query)
    {
        return $query->where('status', 'on_duty');
    }
}