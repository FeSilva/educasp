<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProtocolosMultiplosVistorias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolosMultiplos_vistorias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('protocolo_id');
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
        Schema::dropIfExists('protocolosMultiplos_vistorias');
    }
}
