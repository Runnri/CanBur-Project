<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('itineraries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('destination_id')->constrained()->onDelete('cascade'); // Relasi ke tabel destinasi
        $table->integer('day_number'); // Contoh: Hari ke-1, Hari ke-2 [cite: 9]
        $table->time('activity_time'); // Contoh: Jam 07:00 [cite: 10]
        $table->string('location'); // Lokasi kegiatan [cite: 8]
        $table->text('description'); // Deskripsi kegiatan atau keindahan tempat [cite: 8]
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itineraries');
    }
};
