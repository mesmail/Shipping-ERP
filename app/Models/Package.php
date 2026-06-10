<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'package_number', 'description',
        'length', 'width', 'height', 'actual_weight',
        'volumetric_weight', 'chargeable_weight', 'notes',
    ];

    protected $casts = [
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'actual_weight' => 'decimal:3',
        'volumetric_weight' => 'decimal:3',
        'chargeable_weight' => 'decimal:3',
    ];

    public function shipment() { return $this->belongsTo(Shipment::class); }

    public function calculateWeights(): void
    {
        $volumetric = round(($this->length * $this->width * $this->height) / 5000, 3);
        $chargeable = max($this->actual_weight, $volumetric);
        $this->volumetric_weight = $volumetric;
        $this->chargeable_weight = $chargeable;
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($package) {
            $package->calculateWeights();
        });
        static::saved(function ($package) {
            $package->shipment->updateTotals();
        });
        static::deleted(function ($package) {
            $package->shipment->updateTotals();
        });
    }
}
