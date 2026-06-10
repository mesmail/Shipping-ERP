<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'phone_alt', 'id_number',
        'license_number', 'vehicle_number', 'branch_id', 'is_active', 'notes',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function shipments() { return $this->hasMany(Shipment::class); }
    public function scopeActive($query) { return $query->where('is_active', true); }
}
