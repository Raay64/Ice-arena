<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('fio');
            $table->string('phone');
            $table->integer('hours');
            $table->foreignId('skate_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('total_amount');
            $table->string('payment_id')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_paid')->default(false);
            $table->boolean('has_skates')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
