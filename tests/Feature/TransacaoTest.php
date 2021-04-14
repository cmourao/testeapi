<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TransacaoTest extends TestCase
{

    public function testRequiredFields()
    {
        $this->json('POST', 'transaction', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "value" => ["The value field is required."],
                "payer" => ["The payer field is required."],
                "payee" => ["The payee field is required."]
            ]);
    }

    public function testValorNegativo()
    {
        $this->json('POST', 'transaction', ["value" => -10, "payer" => 4, "payee" => 15], ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "value" => ["The value must be greater than 0.00."]
            ]);
    }

    public function testMesmaPessoaPagaeRecebe()
    {
        $this->json('POST', 'transaction', ["value" => 10, "payer" => 4, "payee" => 4], ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson(
                ["A transação não pode ser realizada para a mesma pessoa"]
            );
    }

    public function testPagadorNaoExiste()
    {
        $this->call('post', 'transaction', ["value" => 10, "payer" => 40, "payee" => 15], ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson(
                ["O pagador não existe"]
            );
    }

    public function testBeneficiarioNaoExiste()
    {
        $this->json('POST', 'transaction', ["value" => 10, "payer" => 4, "payee" => 150], ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson(
                ["O beneficiário não existe"]
            );
    }

    public function testFluxoTransacaoNaoPermitido()
    {
        $this->json('POST', 'transaction', ["value" => 10, "payer" => 15, "payee" => 4], ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson(
                ["Fluxo pagamento não permitido"]
            );
    }

    public function testSemSaldo()
    {
        $this->json('POST', 'transaction', ["value" => 1000, "payer" => 4, "payee" => 15], ['Accept' => 'application/json'])
            ->assertStatus(403)
            ->assertJson(
                ["Pagador sem saldo"]
            );
    }

    public function testTransacaoSucesso()
    {
        $this->json('POST', 'transaction', ["value" => 100, "payer" => 4, "payee" => 15], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson(
                ["Transação realizada com sucesso"]
            );
    }

}
