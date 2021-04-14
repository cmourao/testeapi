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

    public function findByPessoaId($pessoaId)
    {
        return $this->carteira->findByPessoaId($pessoaId);
    }

    public function adicionarSaldoTransicao($pessoaOrigemId, $valor)
    {
        //colocar valor a ser pago no saldo de transicao e retirar do saldo atual        
        $pessoaOrigem = $this->carteira->findByPessoaId($pessoaOrigemId);
        $this->carteira->updateByPessoaId($pessoaOrigemId, [
            "saldo_transicao" => $valor,
            "saldo_atual" => $pessoaOrigem["saldo_atual"] - $valor
        ]);
    }

    public function verificarTransacao($pessoaOrigemId, $pessoaDestinoId, $transacaoEfetivada = true)
    {

        $pessoaOrigem = $this->carteira->findByPessoaId($pessoaOrigemId);

        $valorRetorno = $transacaoEfetivada ? 0 : $pessoaOrigem["saldo_transicao"];
        $this->carteira->updateByPessoaId($pessoaOrigemId, [
            "saldo_transicao" => 0,
            "saldo_atual" => $pessoaOrigem["saldo_atual"] + $valorRetorno
        ]);

        $pessoaDestino = $this->carteira->findByPessoaId($pessoaDestinoId);
        if ($transacaoEfetivada) {
            $this->carteira->updateByPessoaId($pessoaDestinoId, [
                "saldo_atual" => $pessoaDestino["saldo_atual"] + $pessoaOrigem["saldo_transicao"]
            ]);
        }
    }
}
