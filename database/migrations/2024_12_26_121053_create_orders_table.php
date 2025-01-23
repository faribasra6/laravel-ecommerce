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
            $table->id();  // Primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key for users
            $table->double('subtotal', 10, 2);  // Total price before discount and shipping
            $table->double('shipping', 10, 2)->nullable();  // Shipping charges (optional)
            $table->string('coupon_code')->nullable();  // Applied coupon code
            $table->double('discount', 10, 2)->nullable()->default(0);  // Discount applied
            $table->double('grand_total', 10, 2);  // Final total after all calculations
        
            // Order details
            $table->enum('status', ['pending', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('payment_method', 100)->nullable();  // Payment method (e.g., card, PayPal)
            $table->string('payment_status', 50)->default('pending');  // Payment status
            $table->string('transaction_id', 255)->nullable();  // Transaction ID
            $table->string('payment_gateway', 100)->nullable();  // Payment gateway used
            $table->string('order_type', 50)->default('physical');  // Order type (physical/digital)
            $table->double('tax', 10, 2)->default(0);  // Tax amount
            $table->timestamp('estimated_delivery')->nullable();  // Estimated delivery date
            $table->string('refund_status', 50)->nullable();  // Refund status
            $table->double('refund_amount', 10, 2)->nullable();  // Refund amount
            $table->string('tracking_number', 255)->nullable();  // Tracking number
            $table->text('coupon_discount')->nullable();  // Coupon discount details
            $table->json('metadata')->nullable();  // Additional metadata (JSON format)
        
            // Customer details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile');
        
            // Address details
            $table->foreignId('country_id')->constrained()->onDelete('cascade');  // Foreign key for countries
            $table->string('address');
            $table->string('apartment')->nullable();  // Apartment/suite (optional)
            $table->string('city');
            $table->string('state');
            $table->string('zip');
        
            // Notes
            $table->text('notes')->nullable();  // Customer notes
        
            $table->timestamps();  // Created and updated timestamps
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
