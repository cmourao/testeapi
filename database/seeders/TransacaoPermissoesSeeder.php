<?php

namespace Database\Seeders;

use App\Models\PessoaTipo;
use App\Models\TransacaoPermissoes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransacaoPermissoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $pessoaFisica = PessoaTipo::where('Descricao', 'Pessoa Física')->first();
        $pessoaJuridica = PessoaTipo::where('Descricao', 'Pessoa Jurídica')->first();

        TransacaoPermissoes::create([
            "pessoa_tipo_origem_id" => $pessoaFisica["id"],
            "pessoa_tipo_destino_id" => $pessoaFisica["id"],
            "permitido" => 1
        ]);
        TransacaoPermissoes::create([
            "pessoa_tipo_origem_id" => $pessoaFisica["id"],
            "pessoa_tipo_destino_id" => $pessoaJuridica["id"],
            "permitido" => 1
        ]);
        TransacaoPermissoes::create([
            "pessoa_tipo_origem_id" => $pessoaJuridica["id"],
            "pessoa_tipo_destino_id" => $pessoaFisica["id"],
            "permitido" => 0
        ]);
        TransacaoPermissoes::create([
            "pessoa_tipo_origem_id" => $pessoaJuridica["id"],
            "pessoa_tipo_destino_id" => $pessoaJuridica["id"],
            "permitido" => 0
        ]);
    }
}
