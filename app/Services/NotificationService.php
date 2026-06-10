<?php

namespace App\Services;

use App\Models\Shipment;
use App\Models\ShipmentNotification;

class NotificationService
{
    public function notifyStatusChange(Shipment $shipment): void
    {
        $statusLabel = app()->getLocale() === 'ar'
            ? __('shipments.status.' . $shipment->status)
            : $this->getEnglishStatus($shipment->status);

        $arMessage = "عزيزي العميل، تم تحديث حالة شحنتك رقم {$shipment->tracking_number} إلى: {$statusLabel}";
        $enMessage = "Dear customer, your shipment {$shipment->tracking_number} status has been updated to: {$statusLabel}";

        // Log in-app notifications
        $this->createNotification($shipment, 'in_app', 'sender', $shipment->sender_phone, null, $arMessage);
        $this->createNotification($shipment, 'in_app', 'receiver', $shipment->receiver_phone, null, $arMessage);
    }

    private function createNotification(
        Shipment $shipment,
        string $channel,
        string $recipientType,
        ?string $phone,
        ?string $email,
        string $message
    ): ShipmentNotification {
        return ShipmentNotification::create([
            'shipment_id'      => $shipment->id,
            'channel'          => $channel,
            'recipient_type'   => $recipientType,
            'recipient_phone'  => $phone,
            'recipient_email'  => $email,
            'message'          => $message,
            'status'           => 'pending',
        ]);
    }

    private function getEnglishStatus(string $status): string
    {
        return [
            'created'           => 'Created',
            'collected'         => 'Collected',
            'in_transit'        => 'In Transit',
            'transferred'       => 'Transferred',
            'arrived_at_branch' => 'Arrived at Branch',
            'out_for_delivery'  => 'Out For Delivery',
            'delivered'         => 'Delivered',
            'received_closed'   => 'Received / Closed',
            'failed_delivery'   => 'Failed Delivery',
            'returned'          => 'Returned',
        ][$status] ?? $status;
    }
}
