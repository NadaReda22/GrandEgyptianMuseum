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
            $table->string('name');
            $table->string('phone');
            $table->string('national_id');
            $table->string('nationality');
            $table->enum('ticket_type', ['entry', 'guided']);
            $table->integer('quantity')->default(1);
            $table->enum('payment_method', ['pay_now', 'pay_museum']);
            $table->string('status')->default('pending');
            $table->decimal('amount', 8, 2)->default(200.00);
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
