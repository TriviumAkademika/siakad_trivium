<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaktuTable extends Migration
{
    public function up()
    {
        Schema::create('waktus', function (Blueprint $table) {
            $table->id('id_waktu');
            $table->string('hari', 10);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
        });
    }

    public function down()
    {
        Schema::dropIfExists('waktus');
    }
}
