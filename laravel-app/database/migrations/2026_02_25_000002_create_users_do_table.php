<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users_DO', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('email', 150);
            $table->string('naam', 255)->nullable();
            $table->string('password_hash', 255);
            $table->enum('role', ['client', 'admin', 'editor'])->default('client')->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->datetime('last_login_at')->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_DO');
    }
};
