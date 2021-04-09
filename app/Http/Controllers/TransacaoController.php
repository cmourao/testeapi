<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransacaoRequest;
use Illuminate\Http\Request;
use App\Services\TransacaoService;
use Illuminate\Support\Facades\Validator;

class TransacaoController extends Controller
{

    protected $transacaoService;

    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }

    public function create(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'value' => 'required',
        //     'payer' => 'required',
        //     'payee' => 'required'
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->getMessageBag(), 200);
        // }

        $create = $this->transacaoService->create($request);
        //return response()->json('sucesso');

        return response()->json($create["msg"], $create["statusCode"]);
    }
}
