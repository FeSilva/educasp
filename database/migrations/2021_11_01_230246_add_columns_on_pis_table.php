<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnPisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pis', function(Blueprint $table) {
            $table->string('numero_contrato')->nullable();
            $table->string('numero_os')->nullable();
            $table->integer('qtde_vistorias_mes')->default(4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pis', function(Blueprint $table) {
            $table->dropColumn('qtde_vistorias_mes');
            $table->dropColumn('numero_os');
            $table->dropColumn('numero_contrato');
        });
    }
}
