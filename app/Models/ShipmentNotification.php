<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentNotification extends Model
{
    protected $fillable = [
        'shipment_id', 'channel', 'recipient_type', 'recipient_phone',
        'recipient_email', 'message', 'status', 'error_message', 'sent_at',
    ];

    protected $casts = ['sent_at' => 'datetime'];

    public function shipment() { return $this->belongsTo(Shipment::class); }
}
