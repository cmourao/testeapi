<?php

namespace App\Services;

use App\Repositories\TransacaoPermissoesRepository;

class TransacaoPermissoesService
{
    protected TransacaoPermissoesRepository $transacaoPermissoes;

    public function __construct(TransacaoPermissoesRepository $transacaoPermissoes)
    {
        $this->transacaoPermissoes = $transacaoPermissoes;
    }

    public function verficarPermissaoTransacao($pessoaTipoOrigemId, $pessoaTipoDestinoId)
    {
        $permissao = $this->transacaoPermissoes->findByPessoaTipoOrigemPessoaTipoDestino($pessoaTipoOrigemId, $pessoaTipoDestinoId);
        return $permissao && $permissao["permitido"] == 1 ? true : false;
    }
}
