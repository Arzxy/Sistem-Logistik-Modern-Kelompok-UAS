<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingLog extends Model
{
    protected $fillable = [
        'package_id',
        'courier_id',
        'warehouse_id',
        'status',
        'location',
        'notes',
        'source_service',
        'logged_at',
    ];

    protected $casts = [
        'package_id' => 'integer',
        'courier_id' => 'integer',
        'warehouse_id' => 'integer',
        'logged_at' => 'datetime',
    ];

    // Scope: filter by package_id
    public function scopeForPackage($query, int $packageId)
    {
        return $query->where('package_id', $packageId)
            ->orderBy('logged_at', 'asc');
    }

    // Format logged_at untuk tampilan
    public function getFormattedTimeAttribute(): string
    {
        return $this->logged_at
            ? $this->logged_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i')
            : '-';
    }
}