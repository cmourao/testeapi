<?php

namespace App\Repositories;

use App\Models\Carteira;

class CarteiraRepository
{
    protected $carteira;

    public function __construct(Carteira $carteira)
    {
        $this->carteira = $carteira;
    }

    public function findByPessoaId($pessoaId)
    {
        return $this->carteira->where('pessoa_id', $pessoaId)->with('pessoa')->first();
    }

    public function updateByPessoaId($pessoaId, $atributos)
    {
        $this->carteira->where('pessoa_id', $pessoaId)->update($atributos);
    }

}
