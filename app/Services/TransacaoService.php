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

        //validacao
        $validator = Validator::make($request->all(), $this->regrasValidacao());
        if ($validator->fails()) {
            return $this->enviarResposta($validator->getMessageBag(), 422);
        }

        //pagador e beneficiario nao podem ser os mesmos
        if($requestAll["payer"] == $requestAll["payee"]){
            return $this->enviarResposta("A transação não pode ser realizada para a mesma pessoa", 403);
        }

        //verificar se carteiras sao validas
        $payer = $carteiraService->findByPessoaId($requestAll["payer"]);
        if (!$payer) {
            return $this->enviarResposta("Pagador não existe", 403);
        }
        $payee = $carteiraService->findByPessoaId($requestAll["payee"]);
        if (!$payee) {
            return $this->enviarResposta("Beneficiário não existe", 403);
        }

        //verificacao se fluxo de pagamento é permitido
        $permitido = $transacaoPermissoes->verficarPermissaoTransacao($payer["pessoa"]["pessoa_tipo_id"], $payee["pessoa"]["pessoa_tipo_id"]);
        if(!$permitido){
            return $this->enviarResposta("Fluxo pagamento não permitido",  403);
        }

        //iniciar pagamento colocando o valor como saldo em transicao em cada conta - não concluida implementacao
        // $carteiraService->iniciarPagamento($requestAll["payer"], $requestAll["payee"], $requestAll["value"]);        

        //verificar servico autorizador externo
        if (!ServicosExternosService::verificarServicoAutorizadorExterno()) {
            return ["msg" => "Pagamento não possui autorização externa", "statusCode" => 403];
        }

        //criar transacao
        $create = $this->transacao->create([
            'pessoa_origem_id' => $requestAll["payer"],
            'pessoa_destino_id' => $requestAll["payee"],
            'valor' => $requestAll["value"],
            'transacao_estado_id' => 1
        ]);

        //reverter transacao - não concluida implementacao
        // if(!$create){

        // }

        //enviar notificacao
        $notificacao = ServicosExternosService::enviarNotificacao() ? "Notificacao enviada" : "Notificação pendente";

        return ["msg" => ["transacao realizada com sucesso, $notificacao"], "statusCode" => 200];
    }

    private function regrasValidacao()
    {
        return [
            'value' => 'required|gt:0.00',
            'payer' => 'required',
            'payee' => 'required'
        ];
    }

    public function enviarResposta($mensagem, $httpStatusCode)
    {
        return ["msg" => $mensagem, "statusCode" => $httpStatusCode];
    }

    // private function enviarNotificacao()
    // {
    //     $response = Http::get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
    //     $body = json_decode($response->body(), true);
    //     return $body["message"] == "Enviado" ? true : false;
    // }

    // private function verificarServicoAutorizadorExterno()
    // {
    //     $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    //     $body = json_decode($response->body(), true);
    //     return $body["message"] == "Autorizado" ? true : false;
    // }

    // private function verificarFluxoPermissaoTransacao($pessoaOrigemId, $pessoaDestinoId)
    // {

    //     //verificar se o pagador existe
    //     $pessoaOrigem = Pessoa::find($pessoaOrigemId);
    //     if (!$pessoaOrigem) return false;

    //     //veririficar se op beneficiario existe
    //     $pessoaDestino = Pessoa::find($pessoaDestinoId);
    //     if (!$pessoaDestino) return false;

    //     //verificar se fluxo é permitido de acordo com tipo da pessoa
    //     $permissao = TransacaoPermissoes::where([
    //         "pessoa_tipo_origem_id" => $pessoaOrigem["pessoa_tipo_id"],
    //         "pessoa_tipo_destino_id" => $pessoaDestino["pessoa_tipo_id"],
    //     ])->first();
    //     if ($permissao["permitido"] == 1) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}
