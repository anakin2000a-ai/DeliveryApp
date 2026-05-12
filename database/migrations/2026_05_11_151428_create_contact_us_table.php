<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('name', 150);
            $table->string('email', 150)->required();
            $table->string('phone', 30)->nullable();

            $table->string('subject', 200);
            $table->text('message')->required();

            $table->enum('status', ['new', 'read', 'replied', 'closed'])
                ->default('new');

            $table->timestamps();

            $table->index('user_id');
            $table->index('email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_us');
    }
};