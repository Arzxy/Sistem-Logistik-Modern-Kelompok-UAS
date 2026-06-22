<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Package extends Model
{
    protected $fillable = [
        'resi_number',
        'sender_id',
        'receiver_id',
        'origin_warehouse_id',
        'dest_warehouse_id',
		'alamat_tujuan',
        'weight_kg',
        'length_cm',
        'width_cm',
        'height_cm',
        'volume_weight_kg',
        'description',
        'total_price',
        'service_type',
        'status',
        'courier_id',
        'delivery_id',
        'created_by',
    ];

    protected $casts = [
        'weight_kg'        => 'float',
        'length_cm'        => 'float',
        'width_cm'         => 'float',
        'height_cm'        => 'float',
        'volume_weight_kg' => 'float',
        'total_price'      => 'float',
    ];

    // ── Generate nomor resi otomatis ─────────────────────────
    // Format: EKS + tanggal (YYYYMMDD) + 6 digit random
    // Contoh: EKS202401150A3F7B
    public static function generateResi(): string
    {
        do {
            $resi = 'EKS' . date('Ymd') . strtoupper(Str::random(6));
        } while (static::where('resi_number', $resi)->exists());

        return $resi;
    }

    // ── Hitung volume weight dari dimensi ────────────────────
    // Rumus standar ekspedisi: (p x l x t) / 6000
    public static function calculateVolumeWeight(
        ?float $l, ?float $w, ?float $h
    ): ?float {
        if (!$l || !$w || !$h) return null;
        return round(($l * $w * $h) / 6000, 2);
    }

    // ── Chargeable weight: mana yang lebih besar ─────────────
    public function getChargeableWeightAttribute(): float
    {
        $vol = $this->volume_weight_kg ?? 0;
        return max($this->weight_kg, $vol);
    }

    // ── Scope filter by status ────────────────────────────────
    public function scopeByStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    // ── Scope filter by kurir ─────────────────────────────────
    public function scopeByCourier($query, ?int $courierId)
    {
        return $courierId ? $query->where('courier_id', $courierId) : $query;
    }
}
