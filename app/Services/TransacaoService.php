<?php

namespace App\Services;

use App\Models\Pessoa;
use App\Models\Transacao;
use App\Models\TransacaoPermissoes;
use App\Repositories\TransacaoRepository;
use App\Services\CarteiraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class TransacaoService
{

    protected $transacao;

    public function __construct(TransacaoRepository $transacao)
    {
        $this->transacao = $transacao;
    }

    public function index()
    {
        return $this->transacao->all();
    }

    public function create(Request $request, CarteiraService $carteiraService)
    {

        $requestAll = $request->all();

        //validacao campos
        $validator = Validator::make($request->all(), [
            'value' => 'required|gt:0.00',
            'payer' => 'required',
            'payee' => 'required'
        ]);
        if ($validator->fails()) {
            return ["msg" => $validator->getMessageBag(), "statusCode" => 422];
        }

        //verificacao se fluxo de pagamento é permitido
        if (!$this->verificarFluxoPermissaoTransacao($requestAll["payer"], $requestAll["payee"])) {
            return ["msg" => "Fluxo pagamento não permitido", "statusCode" => 403];
        }

        //iniciar pagamento colocando o valor como saldo em transicao em cada conta - não concluida implementacao
        // $carteiraService->iniciarPagamento($requestAll["payer"], $requestAll["payee"], $requestAll["value"]);        

        //verificar servico autorizador externo
        if (!$this->verificarServicoAutorizadorExterno()) {
            return ["msg" => "Pagamento não autorizado", "statusCode" => 403];
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
        $notificacao = $this->enviarNotificacao() ? "Notificacao enviada" : "Notificação pendente";

        return ["msg" => ["transacao realizada com sucesso, $notificacao"], "statusCode" => 200];
    }

    private function enviarNotificacao()
    {
        $response = Http::get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
        $body = json_decode($response->body(), true);
        return $body["message"] == "Enviado" ? true : false;
    }

    private function verificarServicoAutorizadorExterno()
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        $body = json_decode($response->body(), true);
        return $body["message"] == "Autorizado" ? true : false;
    }

    private function verificarFluxoPermissaoTransacao($pessoaOrigemId, $pessoaDestinoId)
    {

        //verificar se o pagador existe
        $pessoaOrigem = Pessoa::find($pessoaOrigemId);
        if (!$pessoaOrigem) return false;

        //veririficar se op beneficiario existe
        $pessoaDestino = Pessoa::find($pessoaDestinoId);
        if (!$pessoaDestino) return false;

        //verificar se fluxo é permitido de acordo com tipo da pessoa
        $permissao = TransacaoPermissoes::where([
            "pessoa_tipo_origem_id" => $pessoaOrigem["pessoa_tipo_id"],
            "pessoa_tipo_destino_id" => $pessoaDestino["pessoa_tipo_id"],
        ])->first();
        if ($permissao["permitido"] == 1) {
            return true;
        } else {
            return false;
        }
    }
}
