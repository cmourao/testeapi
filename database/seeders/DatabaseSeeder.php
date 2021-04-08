<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Database\Seeders\PessoaTipoSeeder;
use Database\Seeders\TransacaoEstadoSeeder;
use Database\Seeders\TransacaoPermissoesSeeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(PessoaTipoSeeder::class);
        $this->command->info('Tabela pessoa_tipo carregada!');

        $this->call(TransacaoEstadoSeeder::class);
        $this->command->info('Tabela transacao_estado carregada!');

        $this->call(TransacaoPermissoesSeeder::class);
        $this->command->info('Tabela transacao_permissoes carregada!');

        $this->call(PessoaSeeder::class);
        $this->command->info('Tabela pessoa carregada!');
    }
}
