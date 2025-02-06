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
        Schema::create('standar_institusi', function (Blueprint $table) {
            $table->id('id_standarinstitut');
            //foreign key
            $table->foreignId('id_penetapan')->references('id_penetapan')->on('penetapans')->onDelete('restrict');

            $table->string('namafile');
            $table->string('kategori');
            //foreign key
            $table->foreignId('namaprodi')->references('id_prodi')->on('tabel_prodi')->onDelete('restrict');

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
        Schema::dropIfExists('standar_institusi');
    }
};
