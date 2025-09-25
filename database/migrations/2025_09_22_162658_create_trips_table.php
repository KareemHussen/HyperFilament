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

            $table->foreignId('vehicle_id')->nullable()->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->nullOnDelete();
            $table->foreignId('company_id')->nullable()->nullOnDelete();

            $table->foreignId('from_area')->nullable()->nullOnDelete();
            $table->foreignId('to_area')->nullable()->nullOnDelete();

            $table->tinyInteger('status');
            
            $table->timestamps();
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
