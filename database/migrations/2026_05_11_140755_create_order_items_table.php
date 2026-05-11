<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('menu_item_id')
                ->nullable()
                ->constrained('menu_items')
                ->nullOnDelete();

            $table->string('item_name', 150);
            $table->decimal('item_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('line_total', 10, 2);

            $table->text('customer_note')->nullable();

            $table->timestamps();

            $table->index('order_id');
            $table->index('menu_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};