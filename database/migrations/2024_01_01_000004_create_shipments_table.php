<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->date('shipment_date');
            $table->enum('shipment_type', ['standard', 'express', 'economy', 'fragile', 'documents', 'oversized'])->default('standard');
            $table->enum('status', [
                'created', 'collected', 'in_transit', 'transferred',
                'arrived_at_branch', 'out_for_delivery', 'delivered',
                'received_closed', 'failed_delivery', 'returned'
            ])->default('created');
            $table->enum('delivery_type', ['door_to_door', 'branch_pickup', 'locker'])->default('door_to_door');

            // Branches
            $table->foreignId('origin_branch_id')->constrained('branches');
            $table->foreignId('destination_branch_id')->constrained('branches');
            $table->foreignId('current_branch_id')->constrained('branches');

            // Driver
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();

            // Sender info (snapshot)
            $table->foreignId('sender_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_phone_alt')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('sender_city')->nullable();
            $table->string('sender_country')->nullable();
            $table->string('sender_id_number')->nullable();

            // Receiver info (snapshot)
            $table->foreignId('receiver_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_phone_alt')->nullable();
            $table->string('receiver_address')->nullable();
            $table->string('receiver_city')->nullable();
            $table->string('receiver_country')->nullable();
            $table->text('delivery_instructions')->nullable();

            // Totals (computed)
            $table->integer('total_packages')->default(0);
            $table->decimal('total_actual_weight', 10, 3)->default(0);
            $table->decimal('total_volumetric_weight', 10, 3)->default(0);
            $table->decimal('total_chargeable_weight', 10, 3)->default(0);

            // POD
            $table->string('pod_receiver_name')->nullable();
            $table->string('pod_receiver_id')->nullable();
            $table->text('pod_signature')->nullable();
            $table->timestamp('pod_at')->nullable();

            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
