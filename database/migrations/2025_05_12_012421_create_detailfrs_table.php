<?php

// database/migrations/xxxx_xx_xx_create_detail_frs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('detail_frs', function (Blueprint $table) {
            $table->id('id_detail_frs');
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_frs');
            $table->boolean('status')->default(true);

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal')->onDelete('cascade');
            $table->foreign('id_frs')->references('id_frs')->on('frs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_frs');
    }
};
