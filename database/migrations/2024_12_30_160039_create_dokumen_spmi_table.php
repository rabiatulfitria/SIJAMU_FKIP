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
        Schema::create('dokumen_spmi', function (Blueprint $table) {
            $table->id('id_dokspmi');
            //foreign key
            $table->foreignId('id_penetapan')->references('id_penetapan')->on('penetapans')->onDelete('restrict');

            $table->string('namafile');
            $table->string('kategori');
            $table->text('file');
            $table->timestamps();
        });
    }

    // $table->foreignId('namaprodi')->references('id_prodi')->on('tabel_prodi')->onDelete('restrict');


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dokumen_spmi');
    }
};
