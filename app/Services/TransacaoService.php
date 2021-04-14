<?php

namespace App\Services;

use App\Models\Pessoa;
use App\Models\TransacaoPermissoes;
use App\Repositories\TransacaoRepository;
use App\Services\CarteiraService;
use App\Http\Requests\TransacaoRequest;
use App\Models\Carteira;
use App\Repositories\CarteiraRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Services\TransacaoPermissoesService;

class TransacaoService
{

    protected $transacao;

    public function __construct(TransacaoRepository $transacao)
    {
        $this->transacao = $transacao;
    }

    public function create(Request $request, CarteiraService $carteiraService, TransacaoPermissoesService $transacaoPermissoes)
    {
        //recuperar todos os valores do request
        $requestAll = $request->all();

        //validacao dos campos
        $validator = Validator::make($request->all(), $this->regrasValidacao());
        if ($validator->fails()) {
            return $this->enviarResposta($validator->getMessageBag(), 422);
        }

        //pagador e beneficiario nao podem ser os mesmos
        if ($requestAll["payer"] == $requestAll["payee"]) {
            return $this->enviarResposta("A transação não pode ser realizada para a mesma pessoa", 403);
        }

        //verificar se carteiras sao validas
        $carteiraPayer = $carteiraService->findByPessoaId($requestAll["payer"]);
        if (!$carteiraPayer) {
            return $this->enviarResposta("Pagador não existe", 403);
        }
        $carteiraPayee = $carteiraService->findByPessoaId($requestAll["payee"]);
        if (!$carteiraPayee) {
            return $this->enviarResposta("Beneficiário não existe", 403);
        }

        //verificacao se fluxo de pagamento é permitido
        $permitido = $transacaoPermissoes->verficarPermissaoTransacao(
            $carteiraPayer["pessoa"]["pessoa_tipo_id"],
            $carteiraPayee["pessoa"]["pessoa_tipo_id"]
        );
        if (!$permitido) {
            return $this->enviarResposta("Fluxo pagamento não permitido",  403);
        }

        //pagador tem saldo?        
        if ($carteiraPayer["saldo_atual"] < $requestAll["value"]) {
            return $this->enviarResposta("Pagador sem saldo",  403);
        }        

        //criar transacao
        $createId = $this->transacao->create([
            'pessoa_origem_id' => $requestAll["payer"],
            'pessoa_destino_id' => $requestAll["payee"],
            'valor' => $requestAll["value"],
            'transacao_estado_id' => 1 //pendente
        ]);
        //retornar mensagem caso nao criada transacao
        if (!$createId) {
            $this->enviarResposta("Transação não criada", 403);
        }

        //iniciar pagamento colocando o valor como saldo em transicao em cada conta - não concluida implementacao
        $carteiraService->adicionarSaldoTransicao($requestAll["payer"], $requestAll["value"]);

        //verificar servico autorizador externo
        if (!ServicosExternosService::verificarServicoAutorizadorExterno()) {

            //nao autorizado remove saldo de transicao
            $carteiraService->verificarTransacao($requestAll["payer"], $requestAll["payee"], false);

            //estado da transacao erro
            $this->transacao->update($createId, ["transacao_estado_id" => 4]); //erro

            return $this->enviarResposta("Pagamento não possui autorização externa", 403);
        }

        $carteiraService->verificarTransacao($requestAll["payer"], $requestAll["payee"], true);

        //enviar notificacao
        $notificacaoStatus = ServicosExternosService::enviarNotificacao();
        if ($notificacaoStatus) {
            $this->transacao->update($createId, ["transacao_estado_id" => 3]); //efetivada
        } else {
            $this->transacao->update($createId, ["transacao_estado_id" => 5]); //notificacao pendente
        }

        return $this->enviarResposta("Transacao realizada com sucesso", 200);
    }

    private function regrasValidacao()
    {
        return [
            'value' => 'required|gt:0.00',  //obrigatório, valor maior que zero
            'payer' => 'required',          //obrigatório
            'payee' => 'required'           //obrigatório
        ];
    }

    public function enviarResposta($mensagem, $httpStatusCode)
    {
        return ["msg" => $mensagem, "statusCode" => $httpStatusCode];
    }
}
