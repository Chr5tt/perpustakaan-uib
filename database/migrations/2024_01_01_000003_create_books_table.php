<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->unique();
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->year('year');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(1);
            $table->integer('available')->default(1);
            $table->string('cover')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable()->comment('Lokasi rak buku');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
