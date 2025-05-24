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
        Schema::create('nilais', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('matakuliah_id');
        $table->unsignedBigInteger('mahasiswa_id');
        $table->string('nilai', 2)->nullable();
        $table->string('jenis_nilai', 10)->default('UTS');
        $table->timestamps();

        $table->foreign('matakuliah_id')->references('id_matkul')->on('matkuls')->onDelete('cascade');
        $table->foreign('mahasiswa_id')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */


    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
