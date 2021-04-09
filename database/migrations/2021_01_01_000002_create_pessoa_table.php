<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoa', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('email', 150)->unique();
            $table->string('senha');
            $table->integer('pessoa_tipo_id')->unsigned()->index();
            $table->foreign('pessoa_tipo_id')->references('id')->on('pessoa_tipo');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoa');
    }
}
