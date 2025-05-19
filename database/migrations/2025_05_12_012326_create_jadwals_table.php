<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->unsignedBigInteger('id_kelas');
            $table->unsignedBigInteger('id_matkul');
            $table->unsignedBigInteger('id_dosen'); // dosen utama (wajib)
            $table->unsignedBigInteger('id_dosen_2')->nullable(); // dosen pendamping (optional)
            $table->unsignedBigInteger('id_waktu');
            $table->unsignedBigInteger('id_ruangan');

            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('id_matkul')->references('id_matkul')->on('matkuls')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
            $table->foreign('id_dosen_2')->references('id_dosen')->on('dosen')->onDelete('cascade');
            $table->foreign('id_waktu')->references('id_waktu')->on('waktus')->onDelete('cascade');
            $table->foreign('id_ruangan')->references('id_ruangan')->on('ruangans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};
