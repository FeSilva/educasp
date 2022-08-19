<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsPisTableDocumentsOs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pis', function(Blueprint $table) {
            $table->string('nome_contratada')->nullable();
            $table->integer('numero_gestao_social')->nullable();
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
            $table->dropColumn('numero_gestao_social');
            $table->dropColumn('nome_contratada');
        });
    }
}
