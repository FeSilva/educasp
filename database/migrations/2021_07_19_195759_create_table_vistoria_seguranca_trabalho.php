<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVistoriaSegurancaTrabalho extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vistorias_multiplas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('pi_id');
            $table->bigInteger('tipo_id');
            $table->bigInteger('predio_id');
            $table->bigInteger('fiscal_user_id');
            $table->string('codigo');
            $table->string('cod_predio');
            $table->dateTime('dt_vistoria');
            $table->string('sigla');
            $table->string('num_orcamento');
            $table->integer('seq_orcamento');
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
        Schema::dropIfExists('vistorias_multiplas');
    }
}
