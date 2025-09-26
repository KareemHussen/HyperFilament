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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name' , 100);
            $table->string('email' , 100)->unique();
            $table->string('address' , 150);
            $table->tinyInteger('industry')->nullable();
            $table->string('phone' , 20)->nullable()->unique();
            $table->string('website')->nullable();
            $table->timestamps();


            // For industry filtering and sorting
            $table->index(['industry', 'name'], 'idx_companies_industry_name');

            // For searchable fields
            $table->index(['name', 'email'], 'idx_companies_name_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
