<?php

namespace App\Services;

use App\Repositories\CarteiraRepository;

class CarteiraService
{
    protected $carteira;

    public function __construct(CarteiraRepository $carteira)
    {
        $this->carteira = $carteira;
    }

    public function iniciarPagamento($idPessoaOrigem, $idPessoaDestino, $valor)
    {

        //carteira de origem tem que existir e ter saldo suficiente para transacao
        $pessoaOrigemCarteira = $this->carteira->findByPessoaId($idPessoaOrigem);
        if ($pessoaOrigemCarteira["exists"] && $pessoaOrigemCarteira["saldo_atual"] < $valor) {
            return false;
        }
        $this->carteira->update(
            $pessoaOrigemCarteira["id"],
            ["saldo_atual" => $pessoaOrigemCarteira["saldo_atual"] - $valor]
        );

        //carteira destino tem que existir
        $pessoaDestinoCarteira = $this->carteira->findByPessoaId($idPessoaDestino);
        if ($pessoaDestinoCarteira["exists"]) {
            return false;
        }
    }
}
