<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_number', 'shipment_date', 'shipment_type', 'status', 'delivery_type',
        'origin_branch_id', 'destination_branch_id', 'current_branch_id', 'driver_id',
        'sender_id', 'sender_name', 'sender_phone', 'sender_phone_alt', 'sender_email',
        'sender_address', 'sender_city', 'sender_country', 'sender_id_number',
        'receiver_id', 'receiver_name', 'receiver_phone', 'receiver_phone_alt',
        'receiver_address', 'receiver_city', 'receiver_country', 'delivery_instructions',
        'total_packages', 'total_actual_weight', 'total_volumetric_weight', 'total_chargeable_weight',
        'pod_receiver_name', 'pod_receiver_id', 'pod_signature', 'pod_at',
        'notes', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'pod_at' => 'datetime',
        'total_actual_weight' => 'decimal:3',
        'total_volumetric_weight' => 'decimal:3',
        'total_chargeable_weight' => 'decimal:3',
    ];

    public static array $statuses = [
        'created', 'collected', 'in_transit', 'transferred',
        'arrived_at_branch', 'out_for_delivery', 'delivered',
        'received_closed', 'failed_delivery', 'returned',
    ];

    public static array $statusColors = [
        'created'           => 'blue',
        'collected'         => 'indigo',
        'in_transit'        => 'yellow',
        'transferred'       => 'purple',
        'arrived_at_branch' => 'cyan',
        'out_for_delivery'  => 'orange',
        'delivered'         => 'green',
        'received_closed'   => 'emerald',
        'failed_delivery'   => 'red',
        'returned'          => 'gray',
    ];

    public function originBranch() { return $this->belongsTo(Branch::class, 'origin_branch_id'); }
    public function destinationBranch() { return $this->belongsTo(Branch::class, 'destination_branch_id'); }
    public function currentBranch() { return $this->belongsTo(Branch::class, 'current_branch_id'); }
    public function driver() { return $this->belongsTo(Driver::class); }
    public function sender() { return $this->belongsTo(Customer::class, 'sender_id'); }
    public function receiver() { return $this->belongsTo(Customer::class, 'receiver_id'); }
    public function packages() { return $this->hasMany(Package::class); }
    public function events() { return $this->hasMany(ShipmentEvent::class)->orderBy('event_at', 'desc'); }
    public function notifications() { return $this->hasMany(ShipmentNotification::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'gray';
    }

    public function getStatusLabelAttribute(): string
    {
        return __('shipments.status.' . $this->status);
    }

    public function updateTotals(): void
    {
        $totals = $this->packages()->selectRaw('
            COUNT(*) as total_packages,
            SUM(actual_weight) as total_actual_weight,
            SUM(volumetric_weight) as total_volumetric_weight,
            SUM(chargeable_weight) as total_chargeable_weight
        ')->first();

        $this->update([
            'total_packages'           => $totals->total_packages ?? 0,
            'total_actual_weight'      => $totals->total_actual_weight ?? 0,
            'total_volumetric_weight'  => $totals->total_volumetric_weight ?? 0,
            'total_chargeable_weight'  => $totals->total_chargeable_weight ?? 0,
        ]);
    }

    public static function generateTrackingNumber(): string
    {
        do {
            $number = '7XP' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 9));
        } while (self::where('tracking_number', $number)->exists());

        return $number;
    }

    public function scopeForBranch($query, int $branchId)
    {
        return $query->where(function ($q) use ($branchId) {
            $q->where('origin_branch_id', $branchId)
              ->orWhere('destination_branch_id', $branchId)
              ->orWhere('current_branch_id', $branchId);
        });
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) return $query;
        return $query->where(function ($q) use ($search) {
            $q->where('tracking_number', 'like', "%{$search}%")
              ->orWhere('sender_name', 'like', "%{$search}%")
              ->orWhere('receiver_name', 'like', "%{$search}%")
              ->orWhere('sender_phone', 'like', "%{$search}%")
              ->orWhere('receiver_phone', 'like', "%{$search}%");
        });
    }
}
