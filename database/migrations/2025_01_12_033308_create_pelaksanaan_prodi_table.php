<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelaksanaan_prodi', function (Blueprint $table) {
            $table->id('id_plks_prodi');
            $table->string('periode_tahunakademik');
            $table->string('namafile');
            $table->foreignId('nama_kategori')->references('id_kategori')->on('kategori')->onDelete('restrict');
            $table->foreignId('namaprodi')->references('id_prod')->on('tabel_prodi')->onDelete('restrict');
            $table->text('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelaksanaan_prodi');
    }
};
