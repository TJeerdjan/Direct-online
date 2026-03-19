<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('client_name', 255);
            $table->string('client_title', 255)->nullable();
            $table->string('client_company', 255)->nullable();
            $table->unsignedBigInteger('photo_id')->nullable();
            $table->text('quote');
            $table->tinyInteger('rating')->default(5)->nullable();
            $table->integer('sort_order')->default(0)->nullable();
            $table->boolean('is_visible')->default(false)->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
