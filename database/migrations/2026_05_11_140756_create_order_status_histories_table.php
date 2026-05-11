<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('old_status', [
                'pending',
                'approved',
                'rejected',
                'auto_rejected',
                'requested',
                'paid',
                'not_paid',
            ]);

            $table->enum('new_status', [
                'pending',
                'approved',
                'rejected',
                'auto_rejected',
                'requested',
                'paid',
                'not_paid',
            ]);

            $table->text('note')->nullable();

            $table->timestamp('created_at')->nullable();

            $table->index('order_id');
            $table->index('changed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};