<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->unique();
            $table->string('site_naam', 255)->nullable();
            $table->string('logo', 500)->nullable();
            $table->string('primaire_kleur', 7)->default('#129387')->nullable();
            $table->string('secundaire_kleur', 7)->default('#f59d0e')->nullable();
            $table->text('over_tekst')->nullable();
            $table->string('facebook', 500)->nullable();
            $table->string('instagram', 500)->nullable();
            $table->string('linkedin', 500)->nullable();
            $table->string('twitter', 500)->nullable();
            $table->string('telefoon', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_settings');
    }
};
