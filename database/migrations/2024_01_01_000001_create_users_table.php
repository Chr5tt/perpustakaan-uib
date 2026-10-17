<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('nim_nidn')->nullable()->comment('NIM untuk mahasiswa, NIDN untuk dosen');
            $table->string('phone')->nullable();
            $table->string('program_studi')->nullable();
            $table->enum('role', ['mahasiswa', 'dosen', 'staff', 'admin'])->default('mahasiswa');
            $table->string('photo')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
