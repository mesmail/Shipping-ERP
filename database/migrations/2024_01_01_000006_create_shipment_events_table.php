<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->enum('status', [
                'created', 'collected', 'in_transit', 'transferred',
                'arrived_at_branch', 'out_for_delivery', 'delivered',
                'received_closed', 'failed_delivery', 'returned'
            ]);
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('event_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
