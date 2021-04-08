<?php

namespace Database\Seeders;

use App\Models\PessoaTipo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PessoaTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        PessoaTipo::create(["Descricao" => "Pessoa Física"]);
        PessoaTipo::create(["Descricao" => "Pessoa Jurídica"]);
    }
}
