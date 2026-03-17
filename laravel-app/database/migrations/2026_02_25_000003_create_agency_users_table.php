<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agency_users', function (Blueprint $table) {
            $table->id();
            $table->string('naam', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('role', ['super_admin', 'admin', 'medewerker'])->default('admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agency_users');
    }
};
