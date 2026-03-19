<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects_DO', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('title', 200);
            $table->string('slug', 220);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('external_url', 500)->nullable();
            $table->string('category', 100)->nullable();
            $table->integer('sort_order')->default(0)->nullable();
            $table->boolean('is_visible')->default(true)->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects_DO');
    }
};
