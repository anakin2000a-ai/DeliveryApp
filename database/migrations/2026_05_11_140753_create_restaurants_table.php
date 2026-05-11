<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();

            $table->text('address')->nullable();
            $table->string('city', 150)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('Germany');

            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index(['city', 'postal_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};