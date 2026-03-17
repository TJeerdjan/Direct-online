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
            $table->string('naam', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('role', ['client_admin', 'client_user'])->default('client_admin')->nullable();
            $table->string('taal', 5)->default('nl')->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_DO');
    }
};
