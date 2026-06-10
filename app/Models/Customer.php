<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name', 'phone', 'phone_alt', 'email',
        'address', 'city', 'country', 'id_number', 'type', 'notes',
    ];

    public function sentShipments() { return $this->hasMany(Shipment::class, 'sender_id'); }
    public function receivedShipments() { return $this->hasMany(Shipment::class, 'receiver_id'); }
}
