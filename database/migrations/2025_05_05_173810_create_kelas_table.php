<?php

// First Migration: Create kelas table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->unsignedBigInteger('id_dosen');
            $table->year('tahun_masuk');
            $table->string('prodi', 50);
            $table->string('paralel', 5);
            $table->enum('status', ['AKTIF', 'LULUS'])->default('AKTIF'); // Add status column here
            $table->timestamps(); // Add timestamps if needed
            
            // Foreign key constraint
            $table->foreign('id_dosen')->references('id_dosen')->on('dosen')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
}

// Alternative Solution: If you want to keep separate migrations
// Make sure the AddStatusToKelasTable migration comes AFTER CreateKelasTable

class AddStatusToKelasTable extends Migration
{
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('kelas', 'status')) {
                $table->enum('status', ['AKTIF', 'LULUS'])->default('AKTIF')->after('paralel');
            }
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}