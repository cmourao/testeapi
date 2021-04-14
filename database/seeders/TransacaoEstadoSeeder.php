<?php

namespace Database\Seeders;

use App\Models\TransacaoEstado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransacaoEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        TransacaoEstado::create(["Descricao" => "Pendente"]);
        TransacaoEstado::create(["Descricao" => "Processando"]);
        TransacaoEstado::create(["Descricao" => "Efetivada"]);
        TransacaoEstado::create(["Descricao" => "Erro"]);
        TransacaoEstado::create(["Descricao" => "Notificação pendente"]);
    }
}
