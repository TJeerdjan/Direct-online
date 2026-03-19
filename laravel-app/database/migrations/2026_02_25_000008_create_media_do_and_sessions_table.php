<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_DO', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('file_name', 255);
            $table->string('file_path', 500);
            $table->string('alt_text', 255)->nullable();
            $table->enum('media_type', ['image', 'video', 'document'])->default('image')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->integer('file_size')->nullable();
            $table->string('usage_key', 100)->nullable();
            $table->boolean('is_active')->default(true)->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('client_id')->references('id')->on('klanten_DO')->onDelete('cascade');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_DO');
        Schema::dropIfExists('sessions');
    }
};
