<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')
                  ->constrained('destinations')
                  ->onDelete('cascade'); // otomatis hapus plan kalau destinasi dihapus
            $table->string('title');       // nama plan/hari
            $table->date('date')->nullable(); // tanggal plan, optional
            $table->text('notes')->nullable(); // catatan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};