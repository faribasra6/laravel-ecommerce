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
        Schema::create('coupon_discount', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('max_use'); // Max uses for the coupon globally
            $table->integer('max_user_use'); // Max uses per user
        
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 8, 2); // Discount value based on type
            $table->decimal('minimum_order_amount', 8, 2)->nullable(); // Minimum order amount for the coupon to be valid
        
            $table->dateTime('start_date')->nullable(); // Start date of the coupon validity
            $table->dateTime('end_date')->nullable(); // End date of the coupon validity
            $table->boolean('status')->default(true); // Coupon active or inactive
            $table->integer('usage_count')->default(0); // Track global usage count
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_discount');
    }
};
