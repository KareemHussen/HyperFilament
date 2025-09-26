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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            
            $table->dateTime('start_date');
            $table->dateTime('end_date');

            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();

            $table->foreignId('from_area')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('to_area')->nullable()->constrained('areas')->nullOnDelete();

            $table->tinyInteger('status');
            
            $table->timestamps();



            // For status-based filtering (most common)
            $table->index(['status', 'start_date'], 'idx_trips_status_start_date');
            
            // For company-based queries
            $table->index(['company_id', 'status'], 'idx_trips_company_status');
            
            // For date range queries
            $table->index(['start_date', 'end_date'], 'idx_trips_date_range');
            
            // For driver-based queries
            $table->index(['driver_id', 'status'], 'idx_trips_driver_status');
            
            // For vehicle-based queries
            $table->index(['vehicle_id', 'status'], 'idx_trips_vehicle_status');
            
            // For area-based queries
            $table->index(['from_area', 'to_area'], 'idx_trips_areas');
            
            // For recent trips (dashboard widgets)
            $table->index(['created_at', 'status'], 'idx_trips_created_status');
            
            // For monthly statistics (chart widgets)
            $table->index(['start_date', 'status'], 'idx_trips_start_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
