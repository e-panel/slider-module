<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BuatTabelSlider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('uuid');
            $table->string('judul')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->string('url')->nullable();
            
            $table->integer('new_tab')->nullable();

            $table->integer('id_operator')->nullable();
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
        Schema::dropIfExists('slider');
    }
}
