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

        Pessoa::create([
            "email" => "walter.white@teste.com",
            "senha" => bcrypt("123"),
            "pessoa_tipo_id" => $pessoaFisica["id"]
        ]);
        $walter = Pessoa::where("email", "walter.white@teste.com")->first();
        PessoaFisica::create([
            "nome" => "Walter White",
            "cpf" => "12345678910",
            "pessoa_id" => $walter["id"]
        ]);
        Carteira::create([
            "saldo_atual" => 100,
            "saldo_transicao" => 0,
            "pessoa_id" => $walter["id"]
        ]);

        Pessoa::create([
            "email" => "contato@poloshermanos.com",
            "senha" => bcrypt("123456"),
            "pessoa_tipo_id" => $pessoaJuridica["id"]
        ]);
        $polos = Pessoa::where("email", "contato@poloshermanos.com")->first();
        PessoaJuridica::create([
            "razao_social" => "Polos Hermanos LTDA",
            "cnpj" => "11111111111111",
            "pessoa_id" => $polos["id"]
        ]);
        Carteira::create([
            "saldo_atual" => 1000,
            "saldo_transicao" => 0,
            "pessoa_id" => $polos["id"]
        ]);
    }
}
