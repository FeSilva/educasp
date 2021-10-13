<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableListaEnviosMultiplos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_envios_multiplos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('codigo_lista');
            $table->date("mes");
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
        Schema::dropIfExists('lista_envios_multiplos');
    }
}
