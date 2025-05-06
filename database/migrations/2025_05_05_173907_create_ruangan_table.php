<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuanganTable extends Migration
{
    public function up()
    {
        Schema::create('ruangans', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string('nama_ruangan');
            $table->string('nama_gedung');
            $table->string('kode_ruangan')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruangans');
    }
}
