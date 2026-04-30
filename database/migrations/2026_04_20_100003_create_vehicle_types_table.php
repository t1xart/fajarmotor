<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('vehicle_brands')->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['matic', 'bebek', 'sport', 'trail', 'adventure']);
            $table->unsignedSmallInteger('cc')->nullable();
            $table->unsignedSmallInteger('year_start')->nullable();
            $table->unsignedSmallInteger('year_end')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
