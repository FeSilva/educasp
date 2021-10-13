<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddColumnTipoProtocolo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('protocolos_multiplos', function (Blueprint $table) {
            //
            $table->bigInteger('tipo_id')->after('status');
            $table->string('tipo_vistoria')->after('tipo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('protocolos_multiplos', function (Blueprint $table) {
            //
            $table->dropColumn('tipo_id');
            $table->dropColumn('tipo_vistoria');
        });
    }
}
