<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'package_id',
        'courier_id',
        'origin_warehouse_id',
        'dest_warehouse_id',
        'delivery_type',
        'status',
        'current_location',
        'notes',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
    ];

    protected $casts = [
        'assigned_at'   => 'datetime',
        'picked_up_at'  => 'datetime',
        'delivered_at'  => 'datetime',
    ];

    // Relasi ke kurir (dalam DB yang sama)
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    // Status yang dianggap "selesai" (tidak bisa diupdate lagi)
    public function isFinished(): bool
    {
        return in_array($this->status, ['delivered', 'failed', 'returned']);
    }

    // Scope: delivery milik kurir tertentu
    public function scopeForCourier($query, int $courierId)
    {
        return $query->where('courier_id', $courierId);
    }
}