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
        Schema::create('line_items', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id');
            $table->string('name');
            $table->integer('product_id');
            $table->integer('variation_id');
            $table->integer('quantity');
            $table->string('tax_class')->nullable();
            $table->decimal('subtotal', total: 8, places: 2);
            $table->decimal('subtotal_tax', total: 8, places: 2);
            $table->decimal('total', total: 8, places: 2);
            $table->decimal('total_tax', total: 8, places: 2);
            $table->text('taxes')->nullable();
            $table->text('meta_data')->nullable();
            $table->string('sku');
            $table->decimal('price', total: 8, places: 2);
            $table->text('image')->nullable();
            $table->string('parent_name')->nullable();
            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_items');
    }
};
