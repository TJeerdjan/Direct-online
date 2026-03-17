<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klanten_DO', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 100);
            $table->string('slug', 100)->unique()->nullable();
            $table->string('domain', 255);
            $table->enum('plan', ['early_bird', 'starter', 'growth', 'webshop'])->default('starter')->nullable();
            $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active')->nullable();
            $table->string('contact_naam', 255)->nullable();
            $table->string('contact_email', 255)->nullable();
            $table->string('api_key', 64);
            $table->json('modules')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klanten_DO');
    }
};
