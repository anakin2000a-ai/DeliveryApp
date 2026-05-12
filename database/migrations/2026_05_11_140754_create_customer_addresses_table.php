<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('service_area_id')
                ->nullable()
                ->constrained('service_areas')
                ->nullOnDelete();

            $table->string('label', 100)->nullable();
            $table->text('full_address');

            $table->string('city', 150);
            $table->string('postal_code', 20);
            $table->string('country', 100)->default('Germany');

            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->boolean('is_default')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index(['postal_code', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};