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
        return $this->carteira->where('pessoa_id', $pessoaId)->with('pessoa')->get();
    }

    // public function find($id)
    // {
    //     return $this->carteira->find($id);
    // }

    // public function updateByPessoaId($pessoaId, $atributos)
    // {
    //     return $this->carteira->where('pessoa_id', $pessoaId)->update($atributos);
    // }

    // public function update($id, $atributos)
    // {
    //     return $this->carteira->find($id)->update($atributos);
    // }


}
