<?php

namespace App\Repositories;

use App\Models\Transacao;

class TransacaoRepository
{
    protected $transacao;

    public function __construct(Transacao $transacao)
    {
        $this->transacao = $transacao;
    }

    public function create($atributos)
    {
        return $this->transacao->create($atributos);
    }

}
