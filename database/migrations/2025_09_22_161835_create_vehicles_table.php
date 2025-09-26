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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name' , 100);
            $table->string('plate_number' ,  20)->nullable();
            $table->integer('weight');
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            // For company-based queries (most frequent)
            $table->index(['company_id', 'name'], 'idx_vehicles_company_name');
        
            // For plate number searches
            $table->index('plate_number', 'idx_vehicles_plate');
            
            // For weight-based sorting
            $table->index(['weight', 'company_id'], 'idx_vehicles_weight_company');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
