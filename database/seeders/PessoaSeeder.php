<?php

namespace Database\Seeders;

use App\Models\Carteira;
use App\Models\Pessoa;
use App\Models\PessoaFisica;
use App\Models\PessoaJuridica;
use Illuminate\Database\Seeder;
use App\Models\PessoaTipo;

class PessoaSeeder extends Seeder
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

        $joseId = 4;
        Pessoa::create([
            "id" => $joseId,
            "email" => "jose@teste.com",
            "senha" => bcrypt("123"),
            "pessoa_tipo_id" => $pessoaFisica["id"]
        ]);
        PessoaFisica::create([
            "nome" => "Jose",
            "cpf" => "12345678910",
            "pessoa_id" => $joseId
        ]);        
        Carteira::create([
            "saldo_atual" => 100,
            "saldo_transicao" => 0,
            "pessoa_id" => $joseId
        ]);

        $empresaId = 15;
        Pessoa::create([
            "id" => $empresaId,
            "email" => "empresa@teste.com",
            "senha" => bcrypt("123456"),
            "pessoa_tipo_id" => $pessoaJuridica["id"]
        ]);
        PessoaJuridica::create([
            "razao_social" => "Teste LTDA",
            "cnpj" => "11111111111111",
            "pessoa_id" => $empresaId
        ]);
        Carteira::create([
            "saldo_atual" => 1000,
            "saldo_transicao" => 0,
            "pessoa_id" => $empresaId
        ]);
    }
}
