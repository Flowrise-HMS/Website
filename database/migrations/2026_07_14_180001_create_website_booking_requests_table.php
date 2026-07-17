<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_booking_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('patient_id')->index();
            $table->uuid('service_id')->nullable()->index();
            $table->uuid('branch_id')->nullable()->index();
            $table->string('type');
            $table->string('status')->default('pending');
            $table->timestamp('preferred_starts_at')->nullable();
            $table->timestamp('preferred_ends_at')->nullable();
            $table->text('notes')->nullable();
            $table->uuid('appointment_id')->nullable();
            $table->uuid('waitlist_entry_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_booking_requests');
    }
};
