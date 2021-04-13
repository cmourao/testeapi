<?php

namespace App\Repositories;

use App\Models\TransacaoPermissoes;

class TransacaoPermissoesRepository
{

    protected $transacaoPermissoes;

    public function __construct(TransacaoPermissoes $transacaoPermissoes)
    {
        $this->transacaoPermissoes = $transacaoPermissoes;
    }

    public function findByPessoaTipoOrigemPessoaTipoDestino($pessoaTipoOrigemId, $pessoaTipoDestinoId)
    {
        
    }
}
