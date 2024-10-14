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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('sub_total')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('shipping')->nullable();
            $table->decimal('discount')->nullable();
            $table->decimal('total')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->integer('payment_type')->nullable()->comment('0 => cash, 1 => online');
            $table->string('transaction_id')->nullable();
            $table->integer('status')->nullable()->comment('0 => pending, 1 => complete, 2 => return');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
