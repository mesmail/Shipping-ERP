<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'name_ar', 'code', 'city', 'city_ar',
        'country', 'country_ar', 'address', 'phone', 'email',
        'is_active', 'notes',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getDisplayNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' && $this->name_ar ? $this->name_ar : $this->name;
    }

    public function users() { return $this->hasMany(User::class); }
    public function drivers() { return $this->hasMany(Driver::class); }
    public function originShipments() { return $this->hasMany(Shipment::class, 'origin_branch_id'); }
    public function destinationShipments() { return $this->hasMany(Shipment::class, 'destination_branch_id'); }
    public function currentShipments() { return $this->hasMany(Shipment::class, 'current_branch_id'); }

    public function scopeActive($query) { return $query->where('is_active', true); }
}
