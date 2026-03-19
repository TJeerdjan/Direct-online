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
            $table->unsignedBigInteger('client_id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->string('category', 100)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('sku', 100)->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('variants')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
            $table->index(['client_id', 'is_visible']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
