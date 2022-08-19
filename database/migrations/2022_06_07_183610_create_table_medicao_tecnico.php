<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMedicaoTecnico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao_fiscal', function (Blueprint $table) {
            $table->increments('medicao_fiscal_id');
            $table->integer('medicao_id')->notnull();
            $table->integer('fiscal_id')->notnull();
            $table->string("codigo_fiscal");
            $table->date('dt_inicio')->notnull();
            $table->date('dt_fim')->notnull();
            $table->char('1')->default('I');
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
        Schema::dropIfExists('medicao_tecnico');
    }
}
