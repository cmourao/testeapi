<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transacao', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pessoa_origem_id')->unsigned()->index();
            $table->foreign('pessoa_origem_id')->references('id')->on('pessoa');

            $table->integer('pessoa_destino_id')->unsigned()->index();
            $table->foreign('pessoa_destino_id')->references('id')->on('pessoa');

            $table->integer('transacao_estado_id')->unsigned()->index();
            $table->foreign('transacao_estado_id')->references('id')->on('transacao_estado');
                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacao');
    }
}
