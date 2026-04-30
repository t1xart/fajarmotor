<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('buy_price', 12, 2)->default(0);
            $table->decimal('sell_price', 12, 2)->default(0);
            $table->string('unit')->default('pcs'); // pcs, set, liter, meter
            $table->unsignedInteger('min_stock')->default(5);
            $table->string('shelf_code')->nullable();
            $table->unsignedSmallInteger('warranty_days')->default(0);
            $table->decimal('weight', 8, 2)->nullable(); // grams
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->boolean('is_primary')->default(false);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('product_compatibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types')->cascadeOnDelete();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'vehicle_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_compatibility');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
    }
};
