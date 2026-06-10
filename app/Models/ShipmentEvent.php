<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id', 'status', 'branch_id', 'user_id', 'notes', 'event_at',
    ];

    protected $casts = ['event_at' => 'datetime'];

    public function shipment() { return $this->belongsTo(Shipment::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function getStatusLabelAttribute(): string
    {
        return __('shipments.status.' . $this->status);
    }
}
