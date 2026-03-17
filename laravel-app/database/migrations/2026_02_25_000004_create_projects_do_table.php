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
            $table->string('titel', 255);
            $table->string('slug', 255)->nullable();
            $table->text('beschrijving')->nullable();
            $table->string('categorie', 100)->nullable();
            $table->string('afbeelding', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->integer('volgorde')->default(0)->nullable();
            $table->boolean('is_actief')->default(true)->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects_DO');
    }
};
