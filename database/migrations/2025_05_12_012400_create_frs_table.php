<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('frs', function (Blueprint $table) {
            $table->id('id_frs');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('tahun_ajaran');
            $table->string('semester');
            $table->integer('total_sks')->default(0);
            $table->decimal('ips', 4, 2)->nullable();
            $table->decimal('ipk', 4, 2)->nullable();
            $table->timestamp('tgl_pengisian')->nullable();
            $table->timestamp('tgl_perubahan')->nullable();
            $table->timestamp('tgl_drop')->nullable();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('frs');
    }
};
