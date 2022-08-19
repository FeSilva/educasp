<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableListaVistoriaEnviosMultiplos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lista_vistoria_envios_multiplos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('lista_id');
            $table->bigInteger('vistoria_id');
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
        Schema::dropIfExists('lista_vistoria_envios_multiplos');
    }
}
