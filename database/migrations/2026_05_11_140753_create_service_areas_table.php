<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_areas', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('country', 100)->default('Germany');
            $table->string('postal_code', 20);
            $table->string('city', 150);

            $table->decimal('center_lat', 10, 8)->nullable();
            $table->decimal('center_lng', 11, 8)->nullable();
            $table->decimal('radius_km', 8, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['postal_code', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_areas');
    }
};