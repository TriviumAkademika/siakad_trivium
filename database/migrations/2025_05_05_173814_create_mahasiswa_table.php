<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('mahasiswa', function (Blueprint $table) {
        $table->id('id_mahasiswa');
        $table->unsignedBigInteger('id_kelas');
        $table->string('nama');
        $table->string('nrp')->unique();
        $table->string('semester');
        $table->enum('gender', ['L', 'P']);
        $table->mediumText('alamat');
        $table->string('no_hp');
        $table->timestamps();

        $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
