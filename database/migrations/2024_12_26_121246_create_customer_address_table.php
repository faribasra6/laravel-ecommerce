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
        Schema::create('customer_address', function (Blueprint $table) {
            $table->id();  // Primary key for the customer address
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Foreign key referencing the users table
            $table->string('first_name');  // First name of the customer
            $table->string('last_name');  // Last name of the customer
            $table->string('email');  // Email address of the customer
            $table->string('mobile');  // Mobile number of the customer
            
            // Country information
            $table->foreignId('country_id')->constrained()->onDelete('cascade');  // Foreign key referencing the countries table
            $table->string('address');  // Address line (street address)
            $table->string('apartment')->nullable();  // Optional apartment field
            $table->string('city');  // City field
            $table->string('state')->nullable();  // Nullable state field in case it's not required
            $table->string('zip')->nullable();  // Nullable zip field
            
            $table->timestamps();  // Created and updated at timestamps
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_address');
    }
};
