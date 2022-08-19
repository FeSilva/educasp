<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVistoriaItemAcompanhamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vistoria_item_acompanhamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pi_id');
            $table->foreignId('item_id');
            $table->dateTime('dt_vistoria');
            $table->decimal('progress');
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
        Schema::dropIfExists('vistoria_item_acompanhamento');
    }
}
