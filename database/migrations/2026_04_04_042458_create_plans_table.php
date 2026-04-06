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
            $table->foreignId('destinations_id')
                  ->constrained(
                    table: 'destinations', 
                    column: 'destinations_id' 
      )
                  ->onDelete('cascade');
            $table->integer('hari');              // Hari ke-berapa (1, 2, 3 ...)
            $table->time('jam');                  // Jam kegiatan   (07:00, 12:00 ...)
            $table->text('kegiatan');             // Deskripsi kegiatan
            $table->string('lokasi')->nullable(); // Lokasi (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
