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
            $table->string('naam', 255);
            $table->string('bedrijf', 255)->nullable();
            $table->text('tekst');
            $table->integer('score')->default(5)->nullable();
            $table->boolean('is_actief')->default(true)->nullable();
            $table->integer('volgorde')->default(0)->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
