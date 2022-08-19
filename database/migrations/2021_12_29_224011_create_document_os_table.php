<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentOsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_os', function (Blueprint $table) {
            $table->id();
            $table->string('nome_escola')->nullable();
            $table->string('numero_os')->nullable();
            $table->string('numero_gestao_social')->nullable();
            $table->string('pi')->nullable();
            $table->string('codigo')->nullable();
            $table->string('contrato')->nullable();
            $table->string('nome_contratada')->nullable();
            $table->integer('qtde_vistorias_seguranca_original')->default(0);
            $table->integer('qtde_vistorias_gestao_original')->default(0);
            $table->integer('percentual')->default(0);
            $table->text('justificativa')->nullable();
            $table->integer('qtde_vistorias_complementar_obra')->default(0);
            $table->integer('qtde_vistorias_complementar_seguranca')->default(0);
            $table->integer('qtde_vistorias_complementar_gestao')->default(0);
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
        Schema::dropIfExists('document_os');
    }
}
