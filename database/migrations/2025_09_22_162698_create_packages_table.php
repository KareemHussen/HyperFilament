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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->decimal('weight', 8, 1);
            $table->decimal('length', 8, 1);
            $table->decimal('width', 8, 1);
            $table->decimal('height', 8, 1);
            $table->integer('quantity');
            $table->string('type');
            $table->string('description')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('trip_id')->constrained('trips')->cascadeOnDelete();
            $table->timestamps();


            // For trip-based queries
            $table->index(['trip_id', 'type'], 'idx_packages_trip_type');

            // For weight calculations
            $table->index(['trip_id', 'weight'], 'idx_packages_trip_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
