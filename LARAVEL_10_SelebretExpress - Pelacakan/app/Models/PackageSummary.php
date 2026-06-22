<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageSummary extends Model
{
    protected $fillable = [
        'package_id',
        'resi_number',
        'last_status',
        'last_location',
        'last_updated',
    ];

    protected $casts = [
        'package_id' => 'integer',
        'last_updated' => 'datetime',
    ];

    // Update atau buat summary dari log terbaru
    public static function updateFromLog(TrackingLog $log, string $resiNumber): self
    {
        return self::updateOrCreate(
            ['package_id' => $log->package_id],
            [
                'resi_number' => $resiNumber,
                'last_status' => $log->status,
                'last_location' => $log->location,
                'last_updated' => $log->logged_at,
            ]
        );
    }
}