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
            $table->string('site_name', 255)->nullable();
            $table->string('tagline', 255)->nullable();
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->string('primary_color', 7)->default('#129387')->nullable();
            $table->string('accent_color', 7)->default('#f59d0e')->nullable();
            $table->string('social_instagram', 255)->nullable();
            $table->string('social_linkedin', 255)->nullable();
            $table->string('social_facebook', 255)->nullable();
            $table->string('social_twitter', 255)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_settings');
    }
};
