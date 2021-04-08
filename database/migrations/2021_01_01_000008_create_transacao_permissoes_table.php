<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacaoPermissoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacao_permissoes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('pessoa_tipo_origem_id')->unsigned()->index();
            $table->foreign('pessoa_tipo_origem_id')->references('id')->on('pessoa_tipo');

            $table->integer('pessoa_tipo_destino_id')->unsigned()->index();
            $table->foreign('pessoa_tipo_destino_id')->references('id')->on('pessoa_tipo');

            $table->boolean('permitido');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacao_permissoes');
    }
}
