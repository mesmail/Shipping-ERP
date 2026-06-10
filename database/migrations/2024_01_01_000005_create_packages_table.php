<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->string('package_number');
            $table->string('description')->nullable();
            $table->decimal('length', 10, 2)->default(0);
            $table->decimal('width', 10, 2)->default(0);
            $table->decimal('height', 10, 2)->default(0);
            $table->decimal('actual_weight', 10, 3)->default(0);
            $table->decimal('volumetric_weight', 10, 3)->default(0);
            $table->decimal('chargeable_weight', 10, 3)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
