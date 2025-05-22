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
