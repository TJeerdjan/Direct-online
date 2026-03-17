<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulieren_DO', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('naam', 255);
            $table->string('email', 255);
            $table->string('telefoon', 50)->nullable();
            $table->text('bericht');
            $table->string('bron_pagina', 255)->default('/')->nullable();
            $table->boolean('is_gelezen')->default(false)->nullable();
            $table->boolean('is_gearchiveerd')->default(false)->nullable();
            $table->enum('status', ['nieuw', 'in_behandeling', 'afgehandeld'])->default('nieuw')->nullable();
            $table->timestamp('aangemaakt_op')->useCurrent();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulieren_DO');
    }
};
