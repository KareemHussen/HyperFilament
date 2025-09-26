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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name' , 100);
            $table->string('email' , 100)->unique()->nullable();
            $table->string('phone' , 20)->unique();
            $table->string('license_number' , 8)->unique();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // For company-based queries (most frequent)
            $table->index(['company_id', 'name'], 'idx_drivers_company_name');
        
            // For unique constraints and search
            $table->index(['email', 'phone'], 'idx_drivers_email_phone');
            
            // For license number searches
            $table->index('license_number', 'idx_drivers_license');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
