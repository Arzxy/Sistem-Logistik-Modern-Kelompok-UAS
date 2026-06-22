<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'address',
        'city',
        'is_active',
    ];

    // Sembunyikan password dari response JSON
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi: user (role=agen) punya banyak gudang
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'agent_id');
    }

    // Scope: filter by role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope: hanya user aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}