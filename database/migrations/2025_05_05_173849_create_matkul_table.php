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
        Schema::create('matkuls', function (Blueprint $table) {
            $table->bigIncrements('id_matkul'); // Primary Key
            $table->string('nama_matkul', 255); // Nama mata kuliah
            $table->string('jenis', 50); // Jenis (misalnya, teori atau praktikum)
            $table->integer('sks'); // SKS (Satuan Kredit Semester)
            $table->integer('kapasitas_kelas'); // Kapasitas kelas
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkuls');
    }
};
