<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMedicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicao', function (Blueprint $table) {
            $table->increments('medicao_id');
            $table->integer('qtd_vinculadas');
            $table->integer('user_by');
            $table->date('dt_ini');
            $table->date('dt_fim');
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
        Schema::dropIfExists('medicao');
    }
}
