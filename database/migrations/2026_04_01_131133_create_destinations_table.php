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
    Schema::create('destinations', function (Blueprint $table) {
        $table->bigIncrements('destinations_id'); 
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Menghubungkan destinasi ke user yang login [cite: 14]
        $table->string('title'); // Judul liburan [cite: 6]
        $table->string('image')->nullable(); // Foto destinasi [cite: 6]
        $table->date('departure_date'); // Tanggal keberangkatan [cite: 6]
        $table->decimal('budget', 15, 2); // Budget yang dibutuhkan [cite: 6]
        $table->integer('duration'); // Lama liburan (hari) [cite: 6]
        $table->boolean('is_completed')->default(false); // Status tercapai atau belum [cite: 6]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
