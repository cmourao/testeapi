<?php

namespace App\Services;

use App\Models\Transacao;
use App\Repositories\TransacaoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function create(Request $request)
    {

        //validacao
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'payer' => 'required',
            'payee' => 'required'
        ]);
        if ($validator->fails()) {
            return ["msg" => $validator->getMessageBag(), "statusCode" => 422];
        }

        //criar transacao
        $requestAll = $request->all();
        $this->transacao->create([
            'pessoa_origem_id' => $requestAll["payer"],
             'pessoa_destino_id' => $requestAll["payee"],
            'valor' => $requestAll["value"],
            'transacao_estado_id' => 1
        ]);
        return ["msg" => ["transacao realizada com sucesso"], "statusCode" => 200];

    }
}
